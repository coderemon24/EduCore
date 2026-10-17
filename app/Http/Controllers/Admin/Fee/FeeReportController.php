<?php

namespace App\Http\Controllers\Admin\Fee;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FeeReportController extends Controller
{
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
}
