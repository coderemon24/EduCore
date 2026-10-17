<?php

namespace App\Services;

use App\Models\FeeDiscount;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class FeePaymentService
{
    /**
     * Propose the next sequential receipt number for a school and year.
     */
    public function generateNextReceiptNumber(?int $schoolId = null, ?int $year = null): string
    {
        $schoolId = $schoolId ?? (app()->has('current_school_id') ? app('current_school_id') : School::value('id') ?? 1);
        $year = $year ?? (int) date('Y');
        $prefix = "REC-{$year}-";

        $lastPayment = FeePayment::withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('receipt_number', 'LIKE', "{$prefix}%")
            ->orderByDesc('id')
            ->value('receipt_number');

        $nextSeq = 1;
        if ($lastPayment && preg_match('/^REC-\d{4}-(\d+)$/', $lastPayment, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $nextSeq);
    }

    /**
     * Record an offline payment transaction against an invoice atomically.
     */
    public function recordPayment(array $data, int $schoolId, ?int $receivedBy = null): FeePayment
    {
        $idempotencyToken = $data['idempotency_token'] ?? null;

        // Idempotency check: if token was already processed, return existing payment without duplicating
        if ($idempotencyToken) {
            $existing = FeePayment::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->where('idempotency_token', $idempotencyToken)
                ->first();

            if ($existing) {
                return $existing->load(['invoice.student', 'receiver']);
            }
        }

        return DB::transaction(function () use ($data, $schoolId, $receivedBy, $idempotencyToken) {
            // Pessimistic row locking on invoice
            $invoice = FeeInvoice::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->lockForUpdate()
                ->findOrFail($data['fee_invoice_id']);

            if ($invoice->status === 'paid') {
                throw new InvalidArgumentException('Invoice is already fully paid.');
            }

            if ($invoice->status === 'void') {
                throw new InvalidArgumentException('Cannot collect payment on a voided/cancelled invoice.');
            }

            $amount = round((float) $data['amount'], 2);
            if ($amount <= 0.00) {
                throw new InvalidArgumentException('Payment amount must be greater than zero.');
            }

            $currentDue = (float) $invoice->due_amount;
            if ($amount > $currentDue) {
                throw new InvalidArgumentException("Payment amount ({$amount}) exceeds the current outstanding due balance ({$currentDue}).");
            }

            $receiptNumber = $data['receipt_number'] ?? $this->generateNextReceiptNumber($schoolId);
            $paymentDate = $data['payment_date'] ?? Carbon::today()->toDateString();

            $payment = FeePayment::create([
                'school_id' => $schoolId,
                'receipt_number' => $receiptNumber,
                'fee_invoice_id' => $invoice->id,
                'student_id' => $invoice->student_id,
                'amount' => $amount,
                'payment_date' => $paymentDate,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'cheque_number' => $data['cheque_number'] ?? null,
                'idempotency_token' => $idempotencyToken ?? (string) Str::uuid(),
                'status' => 'completed',
                'received_by' => $receivedBy,
                'notes' => $data['notes'] ?? null,
            ]);

            // Allocate payment amount across invoice line items
            $remainingPayment = $amount;
            $items = $invoice->items()->get();

            foreach ($items as $item) {
                if ($remainingPayment <= 0.00) {
                    break;
                }

                $itemDue = (float) $item->due_amount;
                if ($itemDue <= 0.00) {
                    continue;
                }

                $allocated = min($remainingPayment, $itemDue);
                $newPaid = round((float) $item->paid_amount + $allocated, 2);
                $newDue = max(0.00, round((float) $item->amount - (float) $item->discount_amount - $newPaid, 2));

                $item->update([
                    'paid_amount' => $newPaid,
                    'due_amount' => $newDue,
                ]);

                $remainingPayment = round($remainingPayment - $allocated, 2);
            }

            // Recalculate invoice totals
            $invoice->recalculateBalances();

            return $payment->load(['invoice.student', 'receiver']);
        });
    }

    /**
     * Safely reverse a completed payment transaction without deleting history.
     */
    public function reversePayment(FeePayment $payment, string $reason, int $reversedBy): FeePayment
    {
        return DB::transaction(function () use ($payment, $reason, $reversedBy) {
            $lockedPayment = FeePayment::withoutGlobalScopes()
                ->where('school_id', $payment->school_id)
                ->lockForUpdate()
                ->findOrFail($payment->id);

            if ($lockedPayment->status === 'reversed') {
                throw new InvalidArgumentException('This payment has already been reversed.');
            }

            $invoice = FeeInvoice::withoutGlobalScopes()
                ->where('school_id', $payment->school_id)
                ->lockForUpdate()
                ->findOrFail($payment->fee_invoice_id);

            $lockedPayment->update([
                'status' => 'reversed',
                'reversed_by' => $reversedBy,
                'reversed_at' => Carbon::now(),
                'reversal_reason' => $reason,
            ]);

            // Recalculate invoice line items and totals
            $activePayments = $invoice->completedPayments()->get();
            $totalActivePaid = (float) $activePayments->sum('amount');

            // Reset items paid amounts according to active payments
            $items = $invoice->items()->get();
            $allocator = $totalActivePaid;

            foreach ($items as $item) {
                $baseItemTotal = round((float) $item->amount - (float) $item->discount_amount, 2);
                $allocated = min($allocator, $baseItemTotal);
                $item->update([
                    'paid_amount' => round($allocated, 2),
                    'due_amount' => max(0.00, round($baseItemTotal - $allocated, 2)),
                ]);
                $allocator = max(0.00, round($allocator - $allocated, 2));
            }

            $invoice->recalculateBalances();

            return $lockedPayment->load(['invoice.student', 'reverser']);
        });
    }

    /**
     * Apply an authorized discount or waiver to an invoice.
     */
    public function applyDiscount(FeeInvoice $invoice, array $data, int $authorizedBy): FeeDiscount
    {
        return DB::transaction(function () use ($invoice, $data, $authorizedBy) {
            $lockedInvoice = FeeInvoice::withoutGlobalScopes()
                ->where('school_id', $invoice->school_id)
                ->lockForUpdate()
                ->findOrFail($invoice->id);

            if ($lockedInvoice->status === 'paid' || $lockedInvoice->status === 'void') {
                throw new InvalidArgumentException('Cannot apply discount to a paid or voided invoice.');
            }

            $amount = round((float) $data['amount'], 2);
            if ($amount <= 0.00) {
                throw new InvalidArgumentException('Discount amount must be greater than zero.');
            }

            $currentDue = (float) $lockedInvoice->due_amount;
            if ($amount > $currentDue) {
                throw new InvalidArgumentException("Discount amount ({$amount}) cannot exceed the remaining due balance ({$currentDue}).");
            }

            $discount = FeeDiscount::create([
                'school_id' => $lockedInvoice->school_id,
                'fee_invoice_id' => $lockedInvoice->id,
                'student_id' => $lockedInvoice->student_id,
                'name' => $data['name'] ?? 'Fee Waiver',
                'type' => $data['type'] ?? 'fixed',
                'rate' => $data['rate'] ?? null,
                'amount' => $amount,
                'reason' => $data['reason'] ?? 'Authorized institutional waiver',
                'authorized_by' => $authorizedBy,
            ]);

            $lockedInvoice->recalculateBalances();

            return $discount->load('authorizer');
        });
    }
}
