<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use App\Models\FeeInvoice;
use App\Models\Guardian;
use App\Models\Notice;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\TimetableSlot;
use App\Services\CurrentSchoolContext;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortalDashboardController extends Controller
{
    /**
     * Display the dedicated role-based portal dashboard with zero N+1 queries.
     */
    public function index(Request $request): View
    {
        $user = Auth::guard('web')->user();
        $user->loadMissing(['roles', 'school']);

        $role = $user->portalRole();
        $todayDay = strtolower(Carbon::now()->format('l'));
        $school = CurrentSchoolContext::get() ?? $user->school;

        // 1. Student Dashboard
        if ($role === 'student') {
            $student = Student::with([
                'school',
                'currentEnrollment.academicClass',
                'currentEnrollment.section',
                'currentEnrollment.academicYear',
                'guardians',
            ])->where('user_id', $user->id)
              ->orWhere('email', $user->email)
              ->first();

            $enrollment = $student?->currentEnrollment;
            $todayTimetable = collect();

            if ($student && $enrollment && $enrollment->section_id) {
                $todayTimetable = TimetableSlot::with(['subject', 'teacher'])
                    ->where('school_id', $user->school_id)
                    ->where('section_id', $enrollment->section_id)
                    ->where('day_of_week', $todayDay)
                    ->orderBy('start_time')
                    ->get();
            }

            $totalDays = $student ? StudentAttendance::where('student_id', $student->id)->count() : 0;
            $presentDays = $student ? StudentAttendance::where('student_id', $student->id)->where('status', 'present')->count() : 0;
            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 100.0;
            $attendanceStats = [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'attendance_rate' => $attendanceRate,
            ];

            $latestExamResult = $student ? ExamResult::with(['exam', 'academicClass', 'section'])
                ->where('student_id', $student->id)
                ->where('status', 'published')
                ->latest('published_at')
                ->first() : null;

            $invoices = $student ? FeeInvoice::with(['academicYear'])
                ->where('student_id', $student->id)
                ->latest('due_date')
                ->take(5)
                ->get() : collect();

            $notices = Notice::where('school_id', $user->school_id)
                ->where('status', 'published')
                ->whereIn('target_audience', ['all', 'students'])
                ->latest('published_at')
                ->take(5)
                ->get();

            return view('portal.dashboards.student', compact(
                'user',
                'student',
                'enrollment',
                'todayTimetable',
                'attendanceStats',
                'latestExamResult',
                'invoices',
                'notices',
                'school',
                'role'
            ));
        }

        // 2. Guardian Dashboard
        if ($role === 'guardian') {
            $guardian = Guardian::with([
                'students' => function ($query) {
                    $query->with([
                        'currentEnrollment.academicClass',
                        'currentEnrollment.section',
                        'currentEnrollment.academicYear',
                    ]);
                },
            ])->where('user_id', $user->id)
              ->orWhere('email', $user->email)
              ->orWhere('phone', $user->phone)
              ->first();

            $wards = $guardian ? $guardian->students : collect();
            $wardId = $request->input('ward_id');
            $activeWard = null;

            if ($wardId) {
                $activeWard = $wards->firstWhere('id', (int) $wardId);
            }
            if (! $activeWard) {
                $activeWard = $wards->first();
            }

            $todayTimetable = collect();
            $attendanceStats = ['total_days' => 0, 'present_days' => 0, 'attendance_rate' => 100.0];
            $latestExamResult = null;
            $invoices = collect();

            if ($activeWard) {
                $enrollment = $activeWard->currentEnrollment;
                if ($enrollment && $enrollment->section_id) {
                    $todayTimetable = TimetableSlot::with(['subject', 'teacher'])
                        ->where('school_id', $user->school_id)
                        ->where('section_id', $enrollment->section_id)
                        ->where('day_of_week', $todayDay)
                        ->orderBy('start_time')
                        ->get();
                }

                $totalDays = StudentAttendance::where('student_id', $activeWard->id)->count();
                $presentDays = StudentAttendance::where('student_id', $activeWard->id)->where('status', 'present')->count();
                $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 100.0;
                $attendanceStats = [
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'attendance_rate' => $attendanceRate,
                ];

                $latestExamResult = ExamResult::with(['exam', 'academicClass', 'section'])
                    ->where('student_id', $activeWard->id)
                    ->where('status', 'published')
                    ->latest('published_at')
                    ->first();

                $invoices = FeeInvoice::with(['academicYear'])
                    ->where('student_id', $activeWard->id)
                    ->latest('due_date')
                    ->take(5)
                    ->get();
            }

            $notices = Notice::where('school_id', $user->school_id)
                ->where('status', 'published')
                ->whereIn('target_audience', ['all', 'guardians'])
                ->latest('published_at')
                ->take(5)
                ->get();

            return view('portal.dashboards.guardian', compact(
                'user',
                'guardian',
                'wards',
                'activeWard',
                'todayTimetable',
                'attendanceStats',
                'latestExamResult',
                'invoices',
                'notices',
                'school',
                'role'
            ));
        }

        // 3. Teacher Dashboard
        if ($role === 'teacher') {
            $staff = Staff::with([
                'classTeacherSections.academicClass',
                'subjectAssignments.academicClass',
                'subjectAssignments.subject',
                'subjectAssignments.academicYear',
            ])->where('user_id', $user->id)
              ->orWhere('email', $user->email)
              ->first();

            $teacher = $staff;

            $todaySchedule = collect();
            if ($staff) {
                $todaySchedule = TimetableSlot::with(['academicClass', 'section', 'subject'])
                    ->where('school_id', $user->school_id)
                    ->where('teacher_id', $staff->id)
                    ->where('day_of_week', $todayDay)
                    ->orderBy('start_time')
                    ->get();
            }

            $assignedSections = $staff ? $staff->classTeacherSections : collect();
            $assignedSubjects = $staff ? $staff->subjectAssignments : collect();

            $notices = Notice::where('school_id', $user->school_id)
                ->where('status', 'published')
                ->whereIn('target_audience', ['all', 'teachers'])
                ->latest('published_at')
                ->take(5)
                ->get();

            return view('portal.dashboards.teacher', compact(
                'user',
                'staff',
                'teacher',
                'assignedSections',
                'assignedSubjects',
                'todaySchedule',
                'notices',
                'school',
                'role'
            ));
        }

        // 4. Staff & School Admin Dashboard
        $staff = Staff::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        $studentCount = Student::where('school_id', $user->school_id)->where('status', 'active')->count();
        $staffCount = Staff::where('school_id', $user->school_id)->where('status', 'active')->count();

        $feeStats = null;
        if ($user->hasPermission('fees.view') || $role === 'school_admin') {
            $feeStats = [
                'total_invoices' => FeeInvoice::where('school_id', $user->school_id)->count(),
                'total_due' => FeeInvoice::where('school_id', $user->school_id)->where('status', '!=', 'paid')->sum('due_amount'),
            ];
        }

        $attendanceCount = StudentAttendance::where('school_id', $user->school_id)
            ->whereDate('attendance_date', Carbon::today())
            ->where('status', 'present')
            ->count();

        $notices = Notice::where('school_id', $user->school_id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('portal.dashboards.staff', compact(
            'user',
            'staff',
            'studentCount',
            'staffCount',
            'feeStats',
            'attendanceCount',
            'notices',
            'school',
            'role'
        ));
    }
}

