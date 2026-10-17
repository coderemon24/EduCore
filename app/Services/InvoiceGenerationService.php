<?php

namespace App\Services;

use App\Models\FeeInvoice;
use App\Models\FeeInvoiceItem;
use App\Models\FeeStructure;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InvoiceGenerationService
{
    /**
     * Propose the next sequential invoice number for a school and year.
     */
    public function generateNextInvoiceNumber(?int $schoolId = null, ?int $year = null): string
    {
        $schoolId = $schoolId ?? (app()->has('current_school_id') ? app('current_school_id') : School::value('id') ?? 1);
        $year = $year ?? (int) date('Y');
        $prefix = "INV-{$year}-";

        $lastInvoice = FeeInvoice::withoutGlobalScopes()
            ->where('school_id', $schoolId)
            ->where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderByDesc('id')
            ->value('invoice_number');

        $nextSeq = 1;
        if ($lastInvoice && preg_match('/^INV-\d{4}-(\d+)$/', $lastInvoice, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }

        return sprintf('%s%05d', $prefix, $nextSeq);
    }

    /**
     * Create an individual student invoice with itemized breakdown.
     */
    public function createSingleInvoice(array $data, int $schoolId, ?int $creatorId = null): FeeInvoice
    {
        return DB::transaction(function () use ($data, $schoolId, $creatorId) {
            $student = Student::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->findOrFail($data['student_id']);

            $enrollment = $student->currentEnrollment;
            $academicYearId = $data['academic_year_id'] ?? $enrollment?->academic_year_id;
            $academicClassId = $data['academic_class_id'] ?? $enrollment?->academic_class_id;
            $sectionId = $data['section_id'] ?? $enrollment?->section_id;

            if (! $academicYearId || ! $academicClassId) {
                throw new InvalidArgumentException('Student must have an active academic year and class to be invoiced.');
            }

            $invoiceNumber = $data['invoice_number'] ?? $this->generateNextInvoiceNumber($schoolId);

            $invoice = FeeInvoice::create([
                'school_id' => $schoolId,
                'invoice_number' => $invoiceNumber,
                'student_id' => $student->id,
                'academic_year_id' => $academicYearId,
                'academic_class_id' => $academicClassId,
                'section_id' => $sectionId,
                'title' => $data['title'],
                'issue_date' => $data['issue_date'] ?? Carbon::today()->toDateString(),
                'due_date' => $data['due_date'],
                'subtotal' => 0.00,
                'discount_amount' => 0.00,
                'paid_amount' => 0.00,
                'due_amount' => 0.00,
                'status' => 'unpaid',
                'remarks' => $data['remarks'] ?? null,
                'created_by' => $creatorId,
            ]);

            $subtotal = 0.00;

            if (! empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $itemAmount = round((float) ($item['amount'] ?? 0.00), 2);
                    if ($itemAmount <= 0) {
                        continue;
                    }

                    FeeInvoiceItem::create([
                        'school_id' => $schoolId,
                        'fee_invoice_id' => $invoice->id,
                        'fee_category_id' => $item['fee_category_id'],
                        'fee_structure_id' => $item['fee_structure_id'] ?? null,
                        'name' => $item['name'] ?? 'Fee Item',
                        'amount' => $itemAmount,
                        'discount_amount' => 0.00,
                        'paid_amount' => 0.00,
                        'due_amount' => $itemAmount,
                    ]);

                    $subtotal += $itemAmount;
                }
            }

            $invoice->update([
                'subtotal' => round($subtotal, 2),
                'due_amount' => round($subtotal, 2),
                'status' => Carbon::parse($invoice->due_date)->isPast() && $subtotal > 0 ? 'overdue' : 'unpaid',
            ]);

            return $invoice->load(['items.feeCategory', 'student']);
        });
    }

    /**
     * Generate bulk invoices for all active students in a given class/section.
     *
     * @return array{created_count: int, total_amount: float}
     */
    public function generateBulkClassInvoices(array $data, int $schoolId, ?int $creatorId = null): array
    {
        return DB::transaction(function () use ($data, $schoolId, $creatorId) {
            $academicYearId = (int) $data['academic_year_id'];
            $academicClassId = (int) $data['academic_class_id'];
            $sectionId = ! empty($data['section_id']) ? (int) $data['section_id'] : null;

            // Fetch active enrolled students
            $enrollmentsQuery = StudentEnrollment::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $academicYearId)
                ->where('academic_class_id', $academicClassId)
                ->where('status', 'current');

            if ($sectionId) {
                $enrollmentsQuery->where('section_id', $sectionId);
            }

            $enrollments = $enrollmentsQuery->with('student')->get();

            if ($enrollments->isEmpty()) {
                throw new InvalidArgumentException('No active enrolled students found for the selected academic class and section.');
            }

            // Determine fee items from selected fee structures or custom items
            $feeItems = [];
            if (! empty($data['fee_structure_ids']) && is_array($data['fee_structure_ids'])) {
                $structures = FeeStructure::withoutGlobalScopes()
                    ->where('school_id', $schoolId)
                    ->whereIn('id', $data['fee_structure_ids'])
                    ->get();

                foreach ($structures as $structure) {
                    $feeItems[] = [
                        'fee_category_id' => $structure->fee_category_id,
                        'fee_structure_id' => $structure->id,
                        'name' => $structure->name,
                        'amount' => round((float) $structure->amount, 2),
                    ];
                }
            } elseif (! empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $feeItems[] = [
                        'fee_category_id' => $item['fee_category_id'],
                        'fee_structure_id' => $item['fee_structure_id'] ?? null,
                        'name' => $item['name'],
                        'amount' => round((float) $item['amount'], 2),
                    ];
                }
            }

            if (empty($feeItems)) {
                throw new InvalidArgumentException('At least one valid fee item or fee structure must be selected for invoice generation.');
            }

            $subtotalPerInvoice = array_sum(array_column($feeItems, 'amount'));
            $createdCount = 0;
            $totalInvoicedAmount = 0.00;

            $year = (int) date('Y');
            $prefix = "INV-{$year}-";

            // Determine starting sequence number for this batch
            $lastInvoice = FeeInvoice::withoutGlobalScopes()
                ->where('school_id', $schoolId)
                ->where('invoice_number', 'LIKE', "{$prefix}%")
                ->orderByDesc('id')
                ->value('invoice_number');

            $seq = 1;
            if ($lastInvoice && preg_match('/^INV-\d{4}-(\d+)$/', $lastInvoice, $matches)) {
                $seq = ((int) $matches[1]) + 1;
            }

            $issueDate = $data['issue_date'] ?? Carbon::today()->toDateString();
            $dueDate = $data['due_date'];
            $isOverdue = Carbon::parse($dueDate)->isPast() && $subtotalPerInvoice > 0;

            foreach ($enrollments as $enrollment) {
                if (! $enrollment->student || $enrollment->student->status !== 'active') {
                    continue;
                }

                $invoiceNum = sprintf('%s%05d', $prefix, $seq++);

                $invoice = FeeInvoice::create([
                    'school_id' => $schoolId,
                    'invoice_number' => $invoiceNum,
                    'student_id' => $enrollment->student_id,
                    'academic_year_id' => $academicYearId,
                    'academic_class_id' => $academicClassId,
                    'section_id' => $enrollment->section_id,
                    'title' => $data['title'],
                    'issue_date' => $issueDate,
                    'due_date' => $dueDate,
                    'subtotal' => round($subtotalPerInvoice, 2),
                    'discount_amount' => 0.00,
                    'paid_amount' => 0.00,
                    'due_amount' => round($subtotalPerInvoice, 2),
                    'status' => $isOverdue ? 'overdue' : 'unpaid',
                    'remarks' => $data['remarks'] ?? null,
                    'created_by' => $creatorId,
                ]);

                foreach ($feeItems as $item) {
                    FeeInvoiceItem::create([
                        'school_id' => $schoolId,
                        'fee_invoice_id' => $invoice->id,
                        'fee_category_id' => $item['fee_category_id'],
                        'fee_structure_id' => $item['fee_structure_id'] ?? null,
                        'name' => $item['name'],
                        'amount' => $item['amount'],
                        'discount_amount' => 0.00,
                        'paid_amount' => 0.00,
                        'due_amount' => $item['amount'],
                    ]);
                }

                $createdCount++;
                $totalInvoicedAmount += $subtotalPerInvoice;
            }

            return [
                'created_count' => $createdCount,
                'total_amount' => round($totalInvoicedAmount, 2),
            ];
        });
    }
}
