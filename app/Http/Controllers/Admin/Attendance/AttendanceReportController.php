<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceReportController extends Controller
{
    /**
     * Display the monthly attendance register matrix and analytical reports.
     */
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderByDesc('is_current')->latest('start_date')->get();
        $selectedYearId = $request->input('academic_year_id', $academicYears->firstWhere('is_current', true)?->id ?? $academicYears->first()?->id);

        $classes = AcademicClass::ordered()->get();
        $selectedClassId = $request->input('academic_class_id');
        $selectedSectionId = $request->input('section_id');

        $sections = collect();
        if ($selectedClassId) {
            $sections = Section::where('academic_class_id', $selectedClassId)->orderBy('name')->get();
        }

        $now = Carbon::now();
        $selectedMonth = (int) $request->input('month', $now->month);
        $selectedYear = (int) $request->input('year', $now->year);

        $carbonMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $daysInMonth = $carbonMonth->daysInMonth;
        $startDateStr = $carbonMonth->copy()->startOfMonth()->toDateString();
        $endDateStr = $carbonMonth->copy()->endOfMonth()->toDateString();

        // Load holidays for the month
        $holidays = collect();
        if ($selectedYearId) {
            $holidays = AcademicHoliday::where('academic_year_id', $selectedYearId)
                ->betweenDates($startDateStr, $endDateStr)
                ->get();
        }

        // Build array of days with weekend and holiday flags
        $daysList = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::createFromDate($selectedYear, $selectedMonth, $d);
            $dateStr = $date->toDateString();
            $isWeekend = $date->isSunday(); // or Friday/Sunday depending on calendar
            $holidayMatch = $holidays->first(function ($h) use ($dateStr) {
                return $h->start_date->toDateString() <= $dateStr && $h->end_date->toDateString() >= $dateStr;
            });

            $daysList[$d] = [
                'day' => $d,
                'date' => $dateStr,
                'day_name' => $date->format('D'),
                'is_weekend' => $isWeekend,
                'holiday' => $holidayMatch,
            ];
        }

        $matrix = collect();
        $overallStats = [
            'total_students' => 0,
            'avg_rate' => 0,
            'total_working_days' => collect($daysList)->where('is_weekend', false)->whereNull('holiday')->count(),
        ];

        if ($selectedClassId && $selectedSectionId && $selectedYearId) {
            $enrollments = StudentEnrollment::where('academic_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->where('academic_year_id', $selectedYearId)
                ->where('status', 'current')
                ->with(['student'])
                ->orderBy('roll_number')
                ->get();

            // Load all attendance rows for the month in one single query
            $attendances = StudentAttendance::where('academic_class_id', $selectedClassId)
                ->where('section_id', $selectedSectionId)
                ->whereBetween('attendance_date', [$startDateStr, $endDateStr])
                ->get()
                ->groupBy('student_id');

            $rateSum = 0;

            $matrix = $enrollments->map(function ($enrollment) use ($attendances, $daysList, &$rateSum) {
                $student = $enrollment->student;
                $studentRecords = $attendances->get($student->id, collect())->keyBy(function ($item) {
                    return (int) Carbon::parse($item->attendance_date)->day;
                });

                $daysData = [];
                $presentCount = 0;
                $absentCount = 0;
                $lateCount = 0;
                $halfDayCount = 0;
                $excusedCount = 0;
                $recordedDays = 0;

                foreach ($daysList as $d => $dayMeta) {
                    $record = $studentRecords->get($d);
                    $code = '-';

                    if ($record) {
                        $recordedDays++;
                        switch ($record->status) {
                            case 'present':
                                $code = 'P';
                                $presentCount++;
                                break;
                            case 'absent':
                                $code = 'A';
                                $absentCount++;
                                break;
                            case 'late':
                                $code = 'L';
                                $lateCount++;
                                break;
                            case 'half_day':
                                $code = 'HD';
                                $halfDayCount++;
                                break;
                            case 'excused':
                                $code = 'E';
                                $excusedCount++;
                                break;
                        }
                    } elseif ($dayMeta['holiday']) {
                        $code = 'H';
                    } elseif ($dayMeta['is_weekend']) {
                        $code = 'W';
                    }

                    $daysData[$d] = [
                        'code' => $code,
                        'record' => $record,
                    ];
                }

                $effectiveAttendance = $presentCount + ($halfDayCount * 0.5) + ($lateCount * 0.8);
                $rate = $recordedDays > 0 ? round(($effectiveAttendance / $recordedDays) * 100, 1) : 0;
                $rateSum += $rate;

                return [
                    'student' => $student,
                    'roll_number' => $enrollment->roll_number,
                    'days' => $daysData,
                    'present' => $presentCount,
                    'absent' => $absentCount,
                    'late' => $lateCount,
                    'half_day' => $halfDayCount,
                    'excused' => $excusedCount,
                    'rate' => $rate,
                ];
            });

            $overallStats['total_students'] = $matrix->count();
            $overallStats['avg_rate'] = $matrix->count() > 0 ? round($rateSum / $matrix->count(), 1) : 0;
        }

        return view('admin.attendance.reports.index', compact(
            'academicYears',
            'selectedYearId',
            'classes',
            'selectedClassId',
            'sections',
            'selectedSectionId',
            'selectedMonth',
            'selectedYear',
            'daysList',
            'matrix',
            'overallStats'
        ));
    }
}
