<?php

namespace App\Http\Controllers\Admin\Fee;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\School;
use App\Services\ReportExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FeeReportController extends Controller
{
    public function __construct(
        protected ReportExportService $exportService
    ) {}

    /**
     * Display financial collection register, summaries, and outstanding dues report.
     */
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $methodFilter = $request->input('payment_method', 'all');
        $activeTab = $request->input('tab', 'collections'); // 'collections' or 'dues'

        // 1. Collections Query
        $paymentsQuery = FeePayment::query()
            ->with([
                'student.currentEnrollment.academicClass',
                'invoice',
                'receiver',
            ])
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->latest('id');

        if ($methodFilter && $methodFilter !== 'all') {
            $paymentsQuery->where('payment_method', $methodFilter);
        }

        $payments = $paymentsQuery->paginate(20, ['*'], 'payments_page')->withQueryString();

        // 2. Collection Summaries for selected date range
        $completedPaymentsQuery = FeePayment::query()
            ->where('status', 'completed')
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate);

        $totalCollected = (float) $completedPaymentsQuery->sum('amount');
        $totalTransactions = (int) $completedPaymentsQuery->count();

        // Collections by Payment Method
        $methodBreakdown = FeePayment::query()
            ->where('status', 'completed')
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->select('payment_method', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method');

        // 3. Class-wise Outstanding Dues Breakdown
        $currentYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::latest('id')->first();
        $classDues = [];

        if ($currentYear) {
            $classes = AcademicClass::ordered()->get();

            foreach ($classes as $class) {
                $classInvoices = FeeInvoice::query()
                    ->where('academic_year_id', $currentYear->id)
                    ->where('academic_class_id', $class->id)
                    ->where('status', '!=', 'void');

                $invoiced = (float) $classInvoices->sum('subtotal');
                $discount = (float) $classInvoices->sum('discount_amount');
                $netInvoiced = max(0.00, $invoiced - $discount);
                $collected = (float) $classInvoices->sum('paid_amount');
                $due = (float) $classInvoices->sum('due_amount');
                $collectionRate = $netInvoiced > 0 ? round(($collected / $netInvoiced) * 100, 1) : 0;

                $classDues[] = [
                    'class' => $class,
                    'invoiced' => $invoiced,
                    'discount' => $discount,
                    'net_invoiced' => $netInvoiced,
                    'collected' => $collected,
                    'due' => $due,
                    'rate' => $collectionRate,
                ];
            }
        }

        return view('admin.fees.reports.index', compact(
            'payments',
            'startDate',
            'endDate',
            'methodFilter',
            'activeTab',
            'totalCollected',
            'totalTransactions',
            'methodBreakdown',
            'classDues',
            'currentYear'
        ));
    }

    /**
     * Export Fee Report in PDF, Excel, or CSV format.
     */
    public function export(Request $request)
    {
        $format = strtolower($request->input('format', 'pdf'));
        $activeTab = $request->input('tab', 'collections');
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $methodFilter = $request->input('payment_method', 'all');

        $school = app()->has('current_school_id')
            ? School::find(app('current_school_id'))
            : (Auth::guard('web')->user()?->school ?? School::first());

        if ($activeTab === 'dues') {
            $currentYear = AcademicYear::where('is_current', true)->first() ?? AcademicYear::latest('id')->first();
            $classDues = [];

            if ($currentYear) {
                $classes = AcademicClass::ordered()->get();

                foreach ($classes as $class) {
                    $classInvoices = FeeInvoice::query()
                        ->where('academic_year_id', $currentYear->id)
                        ->where('academic_class_id', $class->id)
                        ->where('status', '!=', 'void');

                    $invoiced = (float) $classInvoices->sum('subtotal');
                    $discount = (float) $classInvoices->sum('discount_amount');
                    $netInvoiced = max(0.00, $invoiced - $discount);
                    $collected = (float) $classInvoices->sum('paid_amount');
                    $due = (float) $classInvoices->sum('due_amount');
                    $collectionRate = $netInvoiced > 0 ? round(($collected / $netInvoiced) * 100, 1) : 0;

                    $classDues[] = [
                        'class' => $class,
                        'invoiced' => $invoiced,
                        'discount' => $discount,
                        'net_invoiced' => $netInvoiced,
                        'collected' => $collected,
                        'due' => $due,
                        'rate' => $collectionRate,
                    ];
                }
            }

            $filename = 'outstanding-dues-by-class-'.($currentYear?->name ? Str::slug($currentYear->name) : date('Y'));

            if ($format === 'csv') {
                $headers = ['Class', 'Invoiced Amount', 'Waivers / Discounts', 'Net Invoiced', 'Collected Amount', 'Outstanding Due', 'Recovery Rate (%)'];
                $rows = array_map(function ($row) {
                    return [
                        $row['class']->name,
                        number_format($row['invoiced'], 2, '.', ''),
                        number_format($row['discount'], 2, '.', ''),
                        number_format($row['net_invoiced'], 2, '.', ''),
                        number_format($row['collected'], 2, '.', ''),
                        number_format($row['due'], 2, '.', ''),
                        $row['rate'].'%',
                    ];
                }, $classDues);

                return $this->exportService->toCsv("{$filename}.csv", $headers, $rows);
            }

            if ($format === 'excel') {
                return $this->exportService->toExcel("{$filename}.xls", 'admin.fees.reports.excel-dues', compact('classDues', 'currentYear', 'school'));
            }

            return $this->exportService->toPdf("{$filename}.pdf", 'admin.fees.reports.pdf-dues', compact('classDues', 'currentYear', 'school'), landscape: true);
        }

        // Collections Register Export
        $paymentsQuery = FeePayment::query()
            ->with([
                'student.currentEnrollment.academicClass',
                'invoice',
                'receiver',
            ])
            ->whereDate('payment_date', '>=', $startDate)
            ->whereDate('payment_date', '<=', $endDate)
            ->latest('payment_date');

        if ($methodFilter && $methodFilter !== 'all') {
            $paymentsQuery->where('payment_method', $methodFilter);
        }

        $payments = $paymentsQuery->get();
        $totalCollected = (float) $payments->where('status', 'completed')->sum('amount');
        $filename = "collections-report-{$startDate}-to-{$endDate}";

        if ($format === 'csv') {
            $headers = ['Receipt #', 'Date', 'Student Name', 'Admission No', 'Class', 'Invoice #', 'Payment Method', 'Cashier', 'Amount', 'Status'];
            $rows = [];
            foreach ($payments as $payment) {
                $rows[] = [
                    $payment->receipt_number,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->student ? ($payment->student->first_name.' '.$payment->student->last_name) : '—',
                    $payment->student?->admission_number ?? '—',
                    $payment->student?->currentEnrollment?->academicClass?->name ?? '—',
                    $payment->invoice?->invoice_number ?? '—',
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    $payment->receiver?->name ?? 'Admin',
                    number_format($payment->amount, 2, '.', ''),
                    strtoupper($payment->status),
                ];
            }

            return $this->exportService->toCsv("{$filename}.csv", $headers, $rows);
        }

        if ($format === 'excel') {
            return $this->exportService->toExcel("{$filename}.xls", 'admin.fees.reports.excel-collections', compact('payments', 'startDate', 'endDate', 'methodFilter', 'totalCollected', 'school'));
        }

        return $this->exportService->toPdf("{$filename}.pdf", 'admin.fees.reports.pdf-collections', compact('payments', 'startDate', 'endDate', 'methodFilter', 'totalCollected', 'school'), landscape: true);
    }
}
