<?php

namespace App\Http\Controllers\Admin\Fee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Fee\StoreInvoiceRequest;
use App\Http\Requests\Admin\Fee\StoreWaiverRequest;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use App\Models\FeeInvoice;
use App\Models\FeeStructure;
use App\Models\Section;
use App\Models\Student;
use App\Services\FeePaymentService;
use App\Services\InvoiceGenerationService;
use Carbon\Carbon;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FeeInvoiceController extends Controller
{
    public function __construct(
        protected InvoiceGenerationService $invoiceService,
        protected FeePaymentService $paymentService
    ) {}

    /**
     * Display invoice directory with metrics, filters, and student search.
     */
    public function index(Request $request): View
    {
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : Auth::guard('web')->user()?->school_id;

        $query = FeeInvoice::query()
            ->with([
                'student.currentEnrollment.academicClass',
                'student.currentEnrollment.section',
                'academicClass',
                'section',
            ])
            ->latest('id');

        // Status Filter
        $statusFilter = $request->input('status', 'all');
        if ($statusFilter && $statusFilter !== 'all') {
            if ($statusFilter === 'overdue') {
                $query->where(function ($q) {
                    $q->where('status', 'overdue')
                        ->orWhere(function ($sub) {
                            $sub->where('due_amount', '>', 0)
                                ->where('due_date', '<', Carbon::today());
                        });
                });
            } else {
                $query->where('status', $statusFilter);
            }
        }

        // Academic Filters
        if ($yearId = $request->input('academic_year_id')) {
            $query->where('academic_year_id', $yearId);
        }

        if ($classId = $request->input('academic_class_id')) {
            $query->where('academic_class_id', $classId);
        }

        if ($sectionId = $request->input('section_id')) {
            $query->where('section_id', $sectionId);
        }

        // Date Range Filter
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('issue_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('issue_date', '<=', $dateTo);
        }

        // Keyword Search (Invoice number, Student Name, Admission Number)
        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('admission_number', 'LIKE', "%{$search}%")
                            ->orWhere('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Aggregate Financial Metrics for current school
        $metricsQuery = FeeInvoice::query();
        if ($yearId) {
            $metricsQuery->where('academic_year_id', $yearId);
        }

        $totalInvoiced = (float) $metricsQuery->sum('subtotal');
        $totalCollected = (float) $metricsQuery->sum('paid_amount');
        $totalOutstanding = (float) $metricsQuery->sum('due_amount');
        $overdueCount = (int) $metricsQuery->where(function ($q) {
            $q->where('status', 'overdue')
                ->orWhere(function ($sub) {
                    $sub->where('due_amount', '>', 0)
                        ->where('due_date', '<', Carbon::today());
                });
        })->count();

        $invoices = $query->paginate(20)->withQueryString();

        $academicYears = AcademicYear::latest('id')->get();
        $academicClasses = AcademicClass::ordered()->get();
        $sections = Section::orderBy('name')->get();

        return view('admin.fees.invoices.index', compact(
            'invoices',
            'academicYears',
            'academicClasses',
            'sections',
            'totalInvoiced',
            'totalCollected',
            'totalOutstanding',
            'overdueCount',
            'statusFilter'
        ));
    }

    /**
     * Show form for single or bulk invoice generation.
     */
    public function create(): View
    {
        $academicYears = AcademicYear::latest('id')->get();
        $academicClasses = AcademicClass::ordered()->get();
        $sections = Section::orderBy('name')->get();
        $feeCategories = FeeCategory::where('is_active', true)->orderBy('name')->get();
        $feeStructures = FeeStructure::where('is_active', true)->with('feeCategory')->get();

        // Active students for single invoice generation
        $students = Student::active()
            ->with(['currentEnrollment.academicClass', 'currentEnrollment.section'])
            ->orderBy('first_name')
            ->limit(300)
            ->get();

        return view('admin.fees.invoices.create', compact(
            'academicYears',
            'academicClasses',
            'sections',
            'feeCategories',
            'feeStructures',
            'students'
        ));
    }

    /**
     * Store single or bulk generated invoices.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $schoolId = app()->has('current_school_id') ? app('current_school_id') : (Auth::guard('web')->user()?->school_id ?? 1);
        $creatorId = Auth::guard('web')->id() ?? Auth::guard('admin')->id();

        try {
            if ($request->input('generation_type') === 'single') {
                $invoice = $this->invoiceService->createSingleInvoice(
                    $request->validated(),
                    $schoolId,
                    $creatorId
                );

                ToastMagic::success("Invoice #{$invoice->invoice_number} created successfully.");

                return redirect()->route('admin.fees.invoices.show', $invoice);
            } else {
                $result = $this->invoiceService->generateBulkClassInvoices(
                    $request->validated(),
                    $schoolId,
                    $creatorId
                );

                $formattedAmount = number_format($result['total_amount'], 2);
                ToastMagic::success("Generated {$result['created_count']} student invoices successfully (Total: {$formattedAmount}).");

                return redirect()->route('admin.fees.invoices.index');
            }
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display detailed invoice breakdown, payment history, and ledger.
     */
    public function show(FeeInvoice $invoice): View
    {
        $invoice->load([
            'student.currentEnrollment.academicClass',
            'student.currentEnrollment.section',
            'student.guardians',
            'academicClass',
            'section',
            'items.feeCategory',
            'payments.receiver',
            'payments.reverser',
            'discounts.authorizer',
            'creator',
        ]);

        return view('admin.fees.invoices.show', compact('invoice'));
    }

    /**
     * Apply an authorized fee waiver or discount to an invoice.
     */
    public function applyWaiver(StoreWaiverRequest $request, FeeInvoice $invoice): RedirectResponse
    {
        $authorizedBy = Auth::guard('web')->id() ?? Auth::guard('admin')->id();

        try {
            $this->paymentService->applyDiscount($invoice, $request->validated(), $authorizedBy);

            ToastMagic::success('Fee waiver/discount applied successfully.');
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());
        }

        return redirect()->route('admin.fees.invoices.show', $invoice);
    }

    /**
     * Void / cancel an unpaid invoice.
     */
    public function void(Request $request, FeeInvoice $invoice): RedirectResponse
    {
        if ($invoice->paid_amount > 0) {
            ToastMagic::error('Cannot void an invoice that has recorded payments. Reverse payments first.');

            return back();
        }

        $reason = $request->input('reason', 'Administrative cancellation');

        $invoice->update([
            'status' => 'void',
            'remarks' => trim(($invoice->remarks ? $invoice->remarks."\n" : '')."Voided: {$reason}"),
        ]);

        ToastMagic::success("Invoice #{$invoice->invoice_number} has been cancelled/voided.");

        return redirect()->route('admin.fees.invoices.show', $invoice);
    }
}
