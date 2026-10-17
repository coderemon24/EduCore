<?php

namespace App\Http\Controllers\Admin\Fee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Fee\ReversePaymentRequest;
use App\Http\Requests\Admin\Fee\StorePaymentRequest;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Services\FeePaymentService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeePaymentController extends Controller
{
    public function __construct(
        protected FeePaymentService $paymentService
    ) {}

    /**
     * Show offline payment collection screen for an invoice.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $invoiceId = $request->input('invoice_id');

        if (! $invoiceId) {
            ToastMagic::info('Please select an invoice from the directory to collect payment.');

            return redirect()->route('admin.fees.invoices.index');
        }

        $invoice = FeeInvoice::with([
            'student.currentEnrollment.academicClass',
            'student.currentEnrollment.section',
            'items.feeCategory',
        ])->findOrFail($invoiceId);

        if ($invoice->status === 'paid') {
            ToastMagic::info("Invoice #{$invoice->invoice_number} is already fully paid.");

            return redirect()->route('admin.fees.invoices.show', $invoice);
        }

        $idempotencyToken = (string) Str::uuid();

        return view('admin.fees.payments.collect', compact('invoice', 'idempotencyToken'));
    }

    /**
     * Store an offline payment submission atomically.
     */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (Auth::guard('web')->user()?->school_id ?? 1);
        $receivedBy = Auth::guard('web')->id() ?? Auth::guard('admin')->id();

        try {
            $payment = $this->paymentService->recordPayment(
                $request->validated(),
                $schoolId,
                $receivedBy
            );

            ToastMagic::success("Payment of {$payment->amount} collected successfully. Receipt #{$payment->receipt_number}");

            return redirect()->route('admin.fees.receipts.show', $payment);
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display printable receipt voucher.
     */
    public function show(FeePayment $payment): View
    {
        $payment->load([
            'invoice.items.feeCategory',
            'invoice.academicClass',
            'invoice.section',
            'student.currentEnrollment.academicClass',
            'student.currentEnrollment.section',
            'receiver',
            'reverser',
        ]);

        return view('admin.fees.receipts.show', compact('payment'));
    }

    /**
     * Reverse a completed payment transaction without deleting history.
     */
    public function reverse(ReversePaymentRequest $request, FeePayment $payment): RedirectResponse
    {
        $reversedBy = Auth::guard('web')->id() ?? Auth::guard('admin')->id();

        try {
            $this->paymentService->reversePayment(
                $payment,
                $request->validated('reversal_reason'),
                $reversedBy
            );

            ToastMagic::success("Payment #{$payment->receipt_number} has been reversed successfully.");
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());
        }

        return redirect()->route('admin.fees.invoices.show', $payment->fee_invoice_id);
    }
}
