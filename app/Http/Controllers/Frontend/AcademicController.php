<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AcademicHoliday;
use App\Models\AcademicYear;
use App\Models\ClassSubjectAssignment;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\GradingScale;
use App\Models\GradeTier;
use App\Models\MarksheetSetting;
use App\Models\Notice;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\TimetableSlot;
use App\Services\CurrentSchoolContext;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AcademicController extends Controller
{
    /**
     * Display class timetables and examination routines with academic year & class filters.
     */
    public function routine(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $years = collect();
        $selectedYear = null;
        $classes = collect();
        $selectedClass = null;
        $sections = collect();
        $selectedSection = null;
        $timetableSlots = collect();

        // 1. Academic Years Filter
        if ($school->exists && Schema::hasTable('academic_years')) {
            $years = AcademicYear::where('school_id', $school->id)
                ->orderByDesc('is_current')
                ->latest('start_date')
                ->get();

            $yearId = $request->input('year_id');
            if ($yearId) {
                $selectedYear = $years->firstWhere('id', (int) $yearId);
            }
            if (! $selectedYear) {
                $selectedYear = $years->firstWhere('is_current', true) ?? $years->first();
            }
        }

        // 2. Academic Classes & Sections
        if ($school->exists && Schema::hasTable('academic_classes')) {
            $classes = AcademicClass::where('school_id', $school->id)
                ->where('status', 'active')
                ->with(['sections' => fn ($q) => $q->where('school_id', $school->id)->where('status', 'active')->orderBy('name')])
                ->orderBy('order_index', 'asc')
                ->orderBy('numeric_code', 'asc')
                ->get();

            $classId = $request->input('class_id');
            if ($classId) {
                $selectedClass = $classes->firstWhere('id', (int) $classId);
            }
            if (! $selectedClass) {
                // Default to Grade 6 if present, otherwise first active class
                $selectedClass = $classes->firstWhere('numeric_code', 6) ?? $classes->first();
            }

            if ($selectedClass) {
                $sections = $selectedClass->sections;
                $sectionId = $request->input('section_id');
                if ($sectionId && $sectionId !== 'all') {
                    $selectedSection = $sections->firstWhere('id', (int) $sectionId);
                }
            }
        }

        // 3. Days of Week Mapping (Bangladesh Secondary School Standard: Sat - Thu)
        $days = [
            'saturday' => 'শনিবার',
            'sunday' => 'রবিবার',
            'monday' => 'সোমবার',
            'tuesday' => 'মঙ্গলবার',
            'wednesday' => 'বুধবার',
            'thursday' => 'বৃহস্পতিবার',
        ];

        $selectedDay = strtolower((string) $request->input('day', 'all'));
        if ($selectedDay !== 'all' && ! array_key_exists($selectedDay, $days)) {
            $selectedDay = 'all';
        }

        // 4. Timetable Slots Query (Zero N+1 Queries with Eager Loading)
        if ($school->exists && $selectedClass && Schema::hasTable('timetable_slots')) {
            $slotsQuery = TimetableSlot::where('school_id', $school->id)
                ->where('academic_class_id', $selectedClass->id)
                ->with([
                    'subject:id,school_id,name,code',
                    'teacher:id,school_id,user_id,first_name,last_name,designation,photo',
                    'teacher.user:id,name,avatar',
                    'section:id,academic_class_id,name',
                    'academicClass:id,name,numeric_code',
                    'academicYear:id,name',
                ]);

            if ($selectedYear) {
                $slotsQuery->where('academic_year_id', $selectedYear->id);
            }

            if ($selectedSection) {
                $slotsQuery->where('section_id', $selectedSection->id);
            }

            if ($selectedDay !== 'all') {
                $slotsQuery->where('day_of_week', $selectedDay);
            }

            $dayOrderCase = "CASE day_of_week
                WHEN 'saturday' THEN 1
                WHEN 'sunday' THEN 2
                WHEN 'monday' THEN 3
                WHEN 'tuesday' THEN 4
                WHEN 'wednesday' THEN 5
                WHEN 'thursday' THEN 6
                WHEN 'friday' THEN 7
                ELSE 8
            END";

            $timetableSlots = $slotsQuery
                ->orderByRaw($dayOrderCase)
                ->orderBy('start_time')
                ->get();
        }

        $groupedSlots = $timetableSlots->groupBy('day_of_week');

        // Telemetry Statistics
        $stats = [
            'total_slots' => $timetableSlots->count(),
            'total_subjects' => $timetableSlots->pluck('subject_id')->unique()->filter()->count(),
            'total_teachers' => $timetableSlots->pluck('teacher_id')->unique()->filter()->count(),
        ];

        // 5. Academic & Exam Circular Notices
        $routineNotices = collect();
        if ($school->exists && Schema::hasTable('notices')) {
            $routineNotices = Notice::where('school_id', $school->id)
                ->where('status', 'published')
                ->whereIn('type', [Notice::TYPE_ACADEMIC, Notice::TYPE_EXAM])
                ->latest('published_at')
                ->take(5)
                ->get();
        }

        return view('frontend.academic.routine', compact(
            'school',
            'years',
            'selectedYear',
            'classes',
            'selectedClass',
            'sections',
            'selectedSection',
            'days',
            'selectedDay',
            'timetableSlots',
            'groupedSlots',
            'stats',
            'routineNotices'
        ));
    }

    /**
     * Display public academic classes, section allocations, and curriculum subjects.
     */
    public function classes(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $classes = collect();
        $selectedClass = null;
        $selectedClassId = null;
        $telemetry = [
            'total_classes' => 0,
            'total_sections' => 0,
            'total_subjects' => 0,
        ];

        if ($school->exists && Schema::hasTable('academic_classes')) {
            $classes = AcademicClass::where('school_id', $school->id)
                ->where('status', 'active')
                ->with([
                    'sections' => function ($query) use ($school) {
                        $query->where('school_id', $school->id)
                            ->where('status', 'active')
                            ->with('classTeacher');
                    },
                    'subjects' => function ($query) use ($school) {
                        $query->where('subjects.school_id', $school->id)
                            ->where('subjects.status', 'active');
                    },
                ])
                ->orderBy('order_index', 'asc')
                ->orderBy('numeric_code', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            // Calculate telemetry metrics across active school classes
            $telemetry['total_classes'] = $classes->count();
            $telemetry['total_sections'] = $classes->sum(fn ($c) => $c->sections->count());

            // Unique subjects across all classes
            $uniqueSubjectIds = $classes->flatMap(fn ($c) => $c->subjects->pluck('id'))->unique();
            $telemetry['total_subjects'] = $uniqueSubjectIds->count();

            $classId = $request->get('class_id', $classes->first()?->id);
            if ($classId) {
                $selectedClassId = (int) $classId;
                $selectedClass = $classes->firstWhere('id', $selectedClassId) ?? $classes->first();
                if ($selectedClass) {
                    $selectedClassId = $selectedClass->id;
                }
            }
        }

        return view('frontend.academic.classes', compact(
            'school',
            'classes',
            'selectedClass',
            'selectedClassId',
            'telemetry'
        ));
    }

    /**
     * Display the public academic holiday calendar and scheduled institutional breaks.
     */
    public function holidays(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $academicYears = collect();
        $selectedYear = null;
        $selectedYearId = null;
        $holidays = collect();
        $holidaysByMonth = collect();
        $nextHoliday = null;
        $telemetry = [
            'total_events' => 0,
            'total_days' => 0,
            'national_holidays' => 0,
            'vacation_breaks' => 0,
            'exam_breaks' => 0,
        ];
        $calendarEvents = [];

        if ($school->exists && Schema::hasTable('academic_years')) {
            $academicYears = AcademicYear::where('school_id', $school->id)
                ->orderByDesc('is_current')
                ->latest('start_date')
                ->get();

            $requestedYearId = $request->input('academic_year_id');
            if ($requestedYearId) {
                $selectedYear = $academicYears->firstWhere('id', (int) $requestedYearId);
            }

            // Fallback to current or first academic session
            if (! $selectedYear) {
                $selectedYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->first();
            }

            $selectedYearId = $selectedYear?->id;
        }

        if ($school->exists && $selectedYear && Schema::hasTable('academic_holidays')) {
            $holidays = AcademicHoliday::where('school_id', $school->id)
                ->where('academic_year_id', $selectedYear->id)
                ->orderBy('start_date', 'asc')
                ->get();

            // Calculate telemetry metrics
            $telemetry['total_events'] = $holidays->count();
            $telemetry['total_days'] = (int) $holidays->sum(function ($h) {
                return $h->start_date->diffInDays($h->end_date) + 1;
            });
            $telemetry['national_holidays'] = $holidays->where('type', 'holiday')->count();
            $telemetry['vacation_breaks'] = $holidays->filter(function ($h) {
                return ($h->start_date->diffInDays($h->end_date) + 1) >= 3;
            })->count();
            $telemetry['exam_breaks'] = $holidays->where('type', 'exam_break')->count();

            // Find closest upcoming holiday from today
            $today = Carbon::today();
            $nextHoliday = $holidays->first(function ($h) use ($today) {
                return $h->end_date->greaterThanOrEqualTo($today);
            }) ?? $holidays->first();

            // Group holidays by month for the annual schedule view
            $holidaysByMonth = $holidays->groupBy(function ($h) {
                return $h->start_date->format('F Y');
            });

            // Format calendar JSON payload for interactive day inspector
            $calendarEvents = $holidays->map(function ($h) {
                $duration = $h->start_date->diffInDays($h->end_date) + 1;

                $typeLabel = match ($h->type) {
                    'holiday' => 'সরকারি ছুটি',
                    'exam_break' => 'পরীক্ষার প্রস্তুতি ছুটি',
                    'weekend' => 'সাপ্তাহিক ছুটি',
                    'unplanned' => 'বিশেষ ছুটি',
                    default => 'ছুটি',
                };

                $typeLabelEn = match ($h->type) {
                    'holiday' => 'Public Holiday',
                    'exam_break' => 'Exam Prep Break',
                    'weekend' => 'Weekend',
                    'unplanned' => 'Special Closure',
                    default => 'Holiday',
                };

                return [
                    'id' => $h->id,
                    'title' => $h->title,
                    'start_date' => $h->start_date->toDateString(),
                    'end_date' => $h->end_date->toDateString(),
                    'type' => $h->type,
                    'type_label' => $typeLabel,
                    'type_label_en' => $typeLabelEn,
                    'duration_days' => $duration,
                    'description' => $h->description ?? 'একাডেমিক ছুটির দিন উপলক্ষে সকল শ্রেণি কার্যক্রম বন্ধ থাকবে।',
                ];
            })->values()->all();
        }

        return view('frontend.academic.holidays', compact(
            'school',
            'academicYears',
            'selectedYear',
            'selectedYearId',
            'holidays',
            'holidaysByMonth',
            'nextHoliday',
            'telemetry',
            'calendarEvents'
        ));
    }

    /**
     * API endpoint to dynamically fetch holiday events for the interactive calendar.
     */
    public function holidayEvents(Request $request): JsonResponse
    {
        $school = CurrentSchoolContext::get();
        if (! $school->exists) {
            return response()->json(['status' => 'error', 'message' => 'School context not resolved'], 404);
        }

        $yearId = $request->input('academic_year_id');
        $holidays = AcademicHoliday::where('school_id', $school->id)
            ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
            ->orderBy('start_date', 'asc')
            ->get();

        $events = $holidays->map(function ($h) {
            $duration = $h->start_date->diffInDays($h->end_date) + 1;

            return [
                'id' => $h->id,
                'title' => $h->title,
                'start_date' => $h->start_date->toDateString(),
                'end_date' => $h->end_date->toDateString(),
                'type' => $h->type,
                'type_label' => match ($h->type) {
                    'holiday' => 'সরকারি ছুটি',
                    'exam_break' => 'পরীক্ষার প্রস্তুতি ছুটি',
                    'weekend' => 'সাপ্তাহিক ছুটি',
                    'unplanned' => 'বিশেষ ছুটি',
                    default => 'ছুটি',
                },
                'duration_days' => $duration,
                'description' => $h->description ?? 'একাডেমিক ছুটির দিন উপলক্ষে সকল শ্রেণি কার্যক্রম বন্ধ থাকবে।',
            ];
        });

        return response()->json([
            'status' => 'success',
            'count' => $events->count(),
            'data' => $events,
        ]);
    }

    /**
     * Display examination results and student marksheets with interactive cohort filtering.
     */
    public function results(Request $request): View
    {
        $school = CurrentSchoolContext::get();

        $academicYears = collect();
        $selectedYear = null;
        $exams = collect();
        $selectedExam = null;
        $academicClasses = collect();
        $selectedClass = null;
        $sections = collect();
        $selectedSection = null;
        $results = collect();
        $telemetry = [
            'appeared' => 0,
            'passed' => 0,
            'failed' => 0,
            'pass_rate' => 0,
            'gpa5_count' => 0,
        ];
        $individualMarksheet = null;
        $gradingScale = null;
        $searchQuery = trim((string) $request->input('roll_or_id', ''));
        $activeTab = $request->input('tab', $searchQuery !== '' ? 'marksheet' : 'merit');

        if ($school->exists) {
            // 1. Academic Years
            if (Schema::hasTable('academic_years')) {
                $academicYears = AcademicYear::where('school_id', $school->id)
                    ->orderByDesc('is_current')
                    ->latest('start_date')
                    ->get();

                $yearId = $request->input('year_id');
                if ($yearId) {
                    $selectedYear = $academicYears->firstWhere('id', (int) $yearId);
                }
                if (! $selectedYear) {
                    $selectedYear = $academicYears->firstWhere('is_current', true) ?? $academicYears->first();
                }
            }

            // 2. Exams Filter (scoped to selected year if available)
            if (Schema::hasTable('exams')) {
                $examQuery = Exam::where('school_id', $school->id);
                if ($selectedYear) {
                    $examQuery->where('academic_year_id', $selectedYear->id);
                }
                $exams = $examQuery->with(['gradingScale.gradeTiers'])->latest('start_date')->get();

                $examId = $request->input('exam_id');
                if ($examId) {
                    $selectedExam = $exams->firstWhere('id', (int) $examId);
                }
                if (! $selectedExam) {
                    $selectedExam = $exams->first();
                }
            }

            // 3. Academic Classes & Sections
            if (Schema::hasTable('academic_classes')) {
                $academicClasses = AcademicClass::where('school_id', $school->id)
                    ->where('status', 'active')
                    ->with(['sections' => fn ($q) => $q->where('school_id', $school->id)->where('status', 'active')->orderBy('name')])
                    ->orderBy('order_index', 'asc')
                    ->orderBy('numeric_code', 'asc')
                    ->get();

                $classId = $request->input('academic_class_id');
                if ($classId) {
                    $selectedClass = $academicClasses->firstWhere('id', (int) $classId);
                }
                if (! $selectedClass) {
                    $selectedClass = $academicClasses->first();
                }

                if ($selectedClass) {
                    $sections = $selectedClass->sections;
                    $sectionId = $request->input('section_id');
                    if ($sectionId && $sectionId !== 'all') {
                        $selectedSection = $sections->firstWhere('id', (int) $sectionId);
                    }
                }
            }

            // 4. Grading Scale Reference (From selected exam or default school grading scale)
            if ($selectedExam && $selectedExam->gradingScale) {
                $gradingScale = $selectedExam->gradingScale;
            } elseif (Schema::hasTable('grading_scales')) {
                $gradingScale = GradingScale::where('school_id', $school->id)
                    ->with(['gradeTiers' => fn ($q) => $q->orderByDesc('point')])
                    ->first();
            }

            // 5. Query Results & Telemetry if Exam & Class are selected
            if ($selectedExam && Schema::hasTable('exam_results')) {
                $query = ExamResult::where('school_id', $school->id)
                    ->where('exam_id', $selectedExam->id)
                    ->with([
                        'student.currentEnrollment',
                        'academicClass:id,name,numeric_code',
                        'section:id,name',
                        'exam:id,name,academic_year_id',
                    ]);

                if ($selectedClass) {
                    $query->where('academic_class_id', $selectedClass->id);
                }

                if ($selectedSection) {
                    $query->where('section_id', $selectedSection->id);
                }

                $results = $query->orderBy('rank_in_class')->get();

                // Telemetry Calculation
                $totalAppeared = $results->count();
                $totalPassed = $results->where('status', 'pass')->count();
                $totalFailed = $results->where('status', 'fail')->count();
                $passRate = $totalAppeared > 0 ? round(($totalPassed / $totalAppeared) * 100, 1) : 0;
                $gpa5Count = $results->where('gpa', '>=', 5.00)->count();

                $telemetry = [
                    'appeared' => $totalAppeared,
                    'passed' => $totalPassed,
                    'failed' => $totalFailed,
                    'pass_rate' => $passRate,
                    'gpa5_count' => $gpa5Count,
                ];

                // 6. Individual Marksheet Lookup (if search query provided or student_id requested)
                $targetStudent = null;
                $studentId = $request->input('student_id');

                if ($studentId) {
                    $targetStudent = Student::where('school_id', $school->id)
                        ->with(['currentEnrollment'])
                        ->find($studentId);
                } elseif ($searchQuery !== '') {
                    // Search by admission_number or currentEnrollment roll_number
                    $targetStudent = Student::where('school_id', $school->id)
                        ->with(['currentEnrollment'])
                        ->where(function ($q) use ($searchQuery, $selectedClass, $selectedSection) {
                            $q->where('admission_number', $searchQuery)
                              ->orWhereHas('currentEnrollment', function ($eq) use ($searchQuery, $selectedClass, $selectedSection) {
                                  $eq->where('roll_number', $searchQuery);
                                  if ($selectedClass) {
                                      $eq->where('academic_class_id', $selectedClass->id);
                                  }
                                  if ($selectedSection) {
                                      $eq->where('section_id', $selectedSection->id);
                                  }
                              });
                        })
                        ->first();
                }

                if ($targetStudent) {
                    $individualMarksheet = $this->buildMarksheetPayload($selectedExam, $targetStudent);
                    $activeTab = 'marksheet';
                }
            }
        }

        return view('frontend.academic.results', compact(
            'school',
            'academicYears',
            'selectedYear',
            'exams',
            'selectedExam',
            'academicClasses',
            'selectedClass',
            'sections',
            'selectedSection',
            'results',
            'telemetry',
            'individualMarksheet',
            'gradingScale',
            'searchQuery',
            'activeTab'
        ));
    }

    /**
     * Helper to prepare complete marksheet data payload for public view.
     */
    protected function buildMarksheetPayload(Exam $exam, Student $student): array
    {
        $exam->loadMissing(['academicYear', 'gradingScale.gradeTiers']);
        $school = $exam->school ?? School::find($exam->school_id) ?? CurrentSchoolContext::get();

        $enrollment = $student->currentEnrollment;
        $classId = $enrollment?->academic_class_id;

        $result = ExamResult::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        $examSubjects = collect();
        if ($classId && Schema::hasTable('exam_subjects')) {
            $examSubjects = ExamSubject::where('exam_id', $exam->id)
                ->where('academic_class_id', $classId)
                ->with(['subject', 'components'])
                ->orderBy('order_index')
                ->get();
        }

        $marks = collect();
        if (Schema::hasTable('exam_marks')) {
            $marks = ExamMark::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->with(['components.examSubjectComponent', 'gradeTier'])
                ->get()
                ->keyBy('exam_subject_id');
        }

        $highestTotalInClass = 0;
        if ($classId && Schema::hasTable('exam_results')) {
            $highestTotalInClass = ExamResult::where('exam_id', $exam->id)
                ->where('academic_class_id', $classId)
                ->max('total_marks_obtained') ?? 0;
        }

        $setting = null;
        if (Schema::hasTable('marksheet_settings')) {
            $setting = MarksheetSetting::firstOrCreate(
                ['school_id' => $school->id ?? 1],
                [
                    'headmaster_title' => 'প্রধান শিক্ষক',
                    'show_highest_marks' => true,
                    'show_attendance_report' => true,
                    'show_extra_activities' => false,
                    'show_gpa_grading_scale' => true,
                ]
            );
        }

        // Optional subject
        $optionalSubjectId = (int) ($enrollment?->optional_subject_id ?? 0);
        if (! $optionalSubjectId && $classId && Schema::hasTable('class_subject_assignments')) {
            $optionalSubjectId = (int) ClassSubjectAssignment::where('academic_class_id', $classId)
                ->where('type', 'optional')
                ->value('subject_id');
        }

        return [
            'exam' => $exam,
            'student' => $student,
            'enrollment' => $enrollment,
            'result' => $result,
            'examSubjects' => $examSubjects,
            'marks' => $marks,
            'highestTotalInClass' => $highestTotalInClass,
            'setting' => $setting,
            'gradingScale' => $exam->gradingScale,
            'optionalSubjectId' => $optionalSubjectId,
        ];
    }
}
