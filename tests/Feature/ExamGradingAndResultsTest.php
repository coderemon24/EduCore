<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamMarkHistory;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\ExamSubjectComponent;
use App\Models\GradeTier;
use App\Models\GradingScale;
use App\Models\MarksheetSetting;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Services\ExamResultService;
use App\Services\GradingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamGradingAndResultsTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected Admin $superAdmin;

    protected AcademicYear $year;

    protected AcademicClass $class;

    protected Section $section;

    protected Subject $mathSubject;

    protected Subject $engSubject;

    protected GradingScale $scale;

    protected Exam $exam;

    protected ExamSubject $mathExamSubj;

    protected ExamSubject $engExamSubj;

    protected ExamSubjectComponent $mathWrittenComp;

    protected ExamSubjectComponent $mathMcqComp;

    protected Student $student1;

    protected Student $student2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Joypurhat Government Girls High School',
            'code' => 'JGGHS-01',
            'status' => 'active',
            'currency' => 'BDT',
        ]);

        app()->instance('current_school_id', $this->school->id);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->year = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
        ]);

        $this->class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Class Nine',
            'numeric_order' => 9,
        ]);

        $this->section = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $this->class->id,
            'name' => 'A',
            'capacity' => 50,
            'status' => 'active',
        ]);

        $this->mathSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'General Mathematics',
            'code' => '109',
            'type' => 'theory',
        ]);

        $this->engSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'English',
            'code' => '107',
            'type' => 'theory',
        ]);

        // Grading scale (National GPA 5.0)
        $this->scale = GradingScale::create([
            'school_id' => $this->school->id,
            'name' => 'National GPA 5.0 Scale',
            'fail_any_fails_all' => true,
            'is_default' => true,
            'is_active' => true,
        ]);

        $tiers = [
            ['grade' => 'A+', 'point' => 5.00, 'min_percentage' => 80.00, 'max_percentage' => 100.00, 'is_pass' => true, 'order_index' => 1],
            ['grade' => 'A',  'point' => 4.00, 'min_percentage' => 70.00, 'max_percentage' => 79.99,  'is_pass' => true, 'order_index' => 2],
            ['grade' => 'A-', 'point' => 3.50, 'min_percentage' => 60.00, 'max_percentage' => 69.99,  'is_pass' => true, 'order_index' => 3],
            ['grade' => 'B',  'point' => 3.00, 'min_percentage' => 50.00, 'max_percentage' => 59.99,  'is_pass' => true, 'order_index' => 4],
            ['grade' => 'C',  'point' => 2.00, 'min_percentage' => 40.00, 'max_percentage' => 49.99,  'is_pass' => true, 'order_index' => 5],
            ['grade' => 'D',  'point' => 1.00, 'min_percentage' => 33.00, 'max_percentage' => 39.99,  'is_pass' => true, 'order_index' => 6],
            ['grade' => 'F',  'point' => 0.00, 'min_percentage' => 0.00,  'max_percentage' => 32.99,  'is_pass' => false, 'order_index' => 7],
        ];

        foreach ($tiers as $t) {
            GradeTier::create(array_merge($t, ['grading_scale_id' => $this->scale->id]));
        }

        // Exam
        $this->exam = Exam::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'grading_scale_id' => $this->scale->id,
            'title' => 'Half Yearly Examination 2026',
            'exam_type' => 'half_yearly',
            'start_date' => Carbon::today()->subDays(5),
            'end_date' => Carbon::today()->addDays(5),
            'status' => 'marks_entry',
            'is_published' => false,
        ]);

        // Math Exam Subject with Written 70 + MCQ 30
        $this->mathExamSubj = ExamSubject::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'subject_id' => $this->mathSubject->id,
            'full_marks' => 100,
            'pass_marks' => 33,
            'is_mandatory' => true,
        ]);

        $this->mathWrittenComp = ExamSubjectComponent::create([
            'exam_subject_id' => $this->mathExamSubj->id,
            'name' => 'Written',
            'max_marks' => 70,
            'pass_marks' => 23,
            'order_index' => 1,
        ]);

        $this->mathMcqComp = ExamSubjectComponent::create([
            'exam_subject_id' => $this->mathExamSubj->id,
            'name' => 'MCQ',
            'max_marks' => 30,
            'pass_marks' => 10,
            'order_index' => 2,
        ]);

        // English Exam Subject (single written 100)
        $this->engExamSubj = ExamSubject::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'subject_id' => $this->engSubject->id,
            'full_marks' => 100,
            'pass_marks' => 33,
            'is_mandatory' => true,
        ]);

        ExamSubjectComponent::create([
            'exam_subject_id' => $this->engExamSubj->id,
            'name' => 'Written',
            'max_marks' => 100,
            'pass_marks' => 33,
            'order_index' => 1,
        ]);

        // Students & Enrollments
        $this->student1 = Student::create([
            'school_id' => $this->school->id,
            'admission_number' => 'SID-2026-001',
            'admission_date' => '2026-01-01',
            'first_name' => 'Farhana',
            'last_name' => 'Akter',
            'gender' => 'female',
            'enrollment_status' => 'active',
        ]);

        StudentEnrollment::create([
            'student_id' => $this->student1->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'roll_number' => '1',
            'enrollment_status' => 'enrolled',
        ]);

        $this->student2 = Student::create([
            'school_id' => $this->school->id,
            'admission_number' => 'SID-2026-002',
            'admission_date' => '2026-01-01',
            'first_name' => 'Nusrat',
            'last_name' => 'Jahan',
            'gender' => 'female',
            'enrollment_status' => 'active',
        ]);

        StudentEnrollment::create([
            'student_id' => $this->student2->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'roll_number' => '2',
            'enrollment_status' => 'enrolled',
        ]);

        MarksheetSetting::create([
            'school_id' => $this->school->id,
            'headmaster_title' => 'Head Master',
            'header_title' => 'Joypurhat Government Girls High School',
            'sub_header_title' => 'HALF YEARLY EXAMINATION-2026',
            'show_highest_marks' => true,
            'show_attendance_report' => true,
            'show_extra_activities' => true,
            'show_gpa_grading_scale' => true,
        ]);
    }

    /**
     * 6.1: Grading scale evaluation, fail_any_fails_all behavior, and component mark bounds.
     */
    public function test_grading_service_evaluates_percentage_to_grade_tier(): void
    {
        $gradingService = app(GradingService::class);

        $tierAplus = $gradingService->calculateGradeTier($this->scale, 85.0);
        $this->assertNotNull($tierAplus);
        $this->assertEquals('A+', $tierAplus->grade);
        $this->assertEquals(5.00, $tierAplus->point);
        $this->assertTrue($tierAplus->is_pass);

        $tierA = $gradingService->calculateGradeTier($this->scale, 72.5);
        $this->assertNotNull($tierA);
        $this->assertEquals('A', $tierA->grade);
        $this->assertEquals(4.00, $tierA->point);

        $tierF = $gradingService->calculateGradeTier($this->scale, 28.0);
        $this->assertNotNull($tierF);
        $this->assertEquals('F', $tierF->grade);
        $this->assertEquals(0.00, $tierF->point);
        $this->assertFalse($tierF->is_pass);
    }

    public function test_fail_any_fails_all_behavior_sets_overall_result_to_f(): void
    {
        $gradingService = app(GradingService::class);
        $resultService = app(ExamResultService::class);

        // Student 1 passes Math (85%) but fails English (20%)
        // Math Mark
        $mathTier = $gradingService->calculateGradeTier($this->scale, 85);
        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'student_id' => $this->student1->id,
            'marks_obtained' => 85,
            'percentage' => 85,
            'grade_tier_id' => $mathTier->id,
            'attendance_status' => 'present',
            'is_passed' => true,
        ]);

        // English Mark (Failed)
        $engTier = $gradingService->calculateGradeTier($this->scale, 20);
        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->engExamSubj->id,
            'student_id' => $this->student1->id,
            'marks_obtained' => 20,
            'percentage' => 20,
            'grade_tier_id' => $engTier->id,
            'attendance_status' => 'present',
            'is_passed' => false,
        ]);

        // Calculate results with fail_any_fails_all = true
        $resultService->calculateExamResults($this->exam, $this->class->id);

        $res = ExamResult::where('exam_id', $this->exam->id)
            ->where('student_id', $this->student1->id)
            ->first();

        $this->assertNotNull($res);
        $this->assertEquals('F', $res->overall_grade);
        $this->assertEquals(0.00, (float) $res->gpa);
        $this->assertEquals('failed', $res->result_status);

        // Now test when fail_any_fails_all is FALSE
        $this->scale->update(['fail_any_fails_all' => false]);
        $resultService->calculateExamResults($this->exam, $this->class->id);

        $resRelaxed = ExamResult::where('exam_id', $this->exam->id)
            ->where('student_id', $this->student1->id)
            ->first();

        // Average GPA: (5.00 + 0.00) / 2 = 2.50; Overall percentage: 52.5% -> Grade B
        $this->assertEquals(2.50, (float) $resRelaxed->gpa);
        $this->assertEquals('B', $resRelaxed->overall_grade);
    }

    public function test_component_mark_bounds_validation(): void
    {
        // Math Written max is 70. Submitting 75 should fail validation.
        $payload = [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'marks' => [
                [
                    'student_id' => $this->student1->id,
                    'attendance_status' => 'present',
                    'components' => [
                        $this->mathWrittenComp->id => 75, // Exceeds max 70
                        $this->mathMcqComp->id => 20,
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.store'), $payload);

        $response->assertSessionHasErrors();
    }

    /**
     * 6.2: Table marks entry batch saving, non-attendance handling, duplicate prevention.
     */
    public function test_table_marks_entry_batch_saves_student_marks(): void
    {
        $payload = [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'marks' => [
                [
                    'student_id' => $this->student1->id,
                    'attendance_status' => 'present',
                    'components' => [
                        $this->mathWrittenComp->id => 60,
                        $this->mathMcqComp->id => 25,
                    ],
                ],
                [
                    'student_id' => $this->student2->id,
                    'attendance_status' => 'absent',
                    'components' => [
                        $this->mathWrittenComp->id => 0,
                        $this->mathMcqComp->id => 0,
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.store'), $payload);

        $response->assertRedirect();

        // Student 1: 60 + 25 = 85 (A+)
        $mark1 = ExamMark::where('exam_subject_id', $this->mathExamSubj->id)
            ->where('student_id', $this->student1->id)
            ->first();

        $this->assertNotNull($mark1);
        $this->assertEquals(85, $mark1->marks_obtained);
        $this->assertEquals(85, $mark1->percentage);
        $this->assertTrue($mark1->is_passed);
        $this->assertEquals('A+', $mark1->gradeTier?->grade);

        // Student 2: Absent
        $mark2 = ExamMark::where('exam_subject_id', $this->mathExamSubj->id)
            ->where('student_id', $this->student2->id)
            ->first();

        $this->assertNotNull($mark2);
        $this->assertEquals('absent', $mark2->attendance_status);
        $this->assertEquals(0, $mark2->marks_obtained);
        $this->assertFalse($mark2->is_passed);
    }

    public function test_marks_entry_duplicate_prevention_updates_existing_record(): void
    {
        // Save initial marks
        $initialPayload = [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'marks' => [
                [
                    'student_id' => $this->student1->id,
                    'attendance_status' => 'present',
                    'components' => [
                        $this->mathWrittenComp->id => 50,
                        $this->mathMcqComp->id => 20,
                    ],
                ],
            ],
        ];

        $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.store'), $initialPayload);

        $this->assertEquals(1, ExamMark::where('exam_subject_id', $this->mathExamSubj->id)->where('student_id', $this->student1->id)->count());

        // Re-submit updated marks for the same student
        $updatedPayload = [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'marks' => [
                [
                    'student_id' => $this->student1->id,
                    'attendance_status' => 'present',
                    'components' => [
                        $this->mathWrittenComp->id => 55,
                        $this->mathMcqComp->id => 25,
                    ],
                ],
            ],
        ];

        $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.store'), $updatedPayload);

        // Count must still be 1 (updated, not duplicated)
        $this->assertEquals(1, ExamMark::where('exam_subject_id', $this->mathExamSubj->id)->where('student_id', $this->student1->id)->count());
        $mark = ExamMark::where('exam_subject_id', $this->mathExamSubj->id)->where('student_id', $this->student1->id)->first();
        $this->assertEquals(80, $mark->marks_obtained);
    }

    /**
     * 6.3: Result calculation, highest mark calculation, rank sorting, publication locking, and post-publish correction.
     */
    public function test_result_calculation_highest_mark_and_rank_sorting(): void
    {
        $gradingService = app(GradingService::class);
        $resultService = app(ExamResultService::class);

        // Student 1: Math 90, English 85 (Total: 175)
        $t90 = $gradingService->calculateGradeTier($this->scale, 90);
        $t85 = $gradingService->calculateGradeTier($this->scale, 85);

        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'student_id' => $this->student1->id,
            'marks_obtained' => 90,
            'percentage' => 90,
            'grade_tier_id' => $t90->id,
            'attendance_status' => 'present',
            'is_passed' => true,
        ]);
        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->engExamSubj->id,
            'student_id' => $this->student1->id,
            'marks_obtained' => 85,
            'percentage' => 85,
            'grade_tier_id' => $t85->id,
            'attendance_status' => 'present',
            'is_passed' => true,
        ]);

        // Student 2: Math 70, English 65 (Total: 135)
        $t70 = $gradingService->calculateGradeTier($this->scale, 70);
        $t65 = $gradingService->calculateGradeTier($this->scale, 65);

        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'student_id' => $this->student2->id,
            'marks_obtained' => 70,
            'percentage' => 70,
            'grade_tier_id' => $t70->id,
            'attendance_status' => 'present',
            'is_passed' => true,
        ]);
        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->engExamSubj->id,
            'student_id' => $this->student2->id,
            'marks_obtained' => 65,
            'percentage' => 65,
            'grade_tier_id' => $t65->id,
            'attendance_status' => 'present',
            'is_passed' => true,
        ]);

        // Calculate results
        $resultService->calculateExamResults($this->exam, $this->class->id);

        $res1 = ExamResult::where('exam_id', $this->exam->id)->where('student_id', $this->student1->id)->first();
        $res2 = ExamResult::where('exam_id', $this->exam->id)->where('student_id', $this->student2->id)->first();

        $this->assertEquals(1, $res1->rank_in_class);
        $this->assertEquals(175, $res1->total_marks_obtained);
        $this->assertEquals(2, $res2->rank_in_class);
        $this->assertEquals(135, $res2->total_marks_obtained);

        // Test subject highest marks
        $highest = $resultService->getSubjectHighestMarks($this->exam, $this->class->id);
        $this->assertEquals(90, $highest[$this->mathExamSubj->id]);
        $this->assertEquals(85, $highest[$this->engExamSubj->id]);
    }

    public function test_published_exam_locks_marks_entry_and_allows_audited_corrections(): void
    {
        // 1. Mark exam as published
        $this->exam->update(['is_published' => true, 'status' => 'published']);

        // Attempting to post standard batch marks entry should be rejected
        $payload = [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'marks' => [
                [
                    'student_id' => $this->student1->id,
                    'attendance_status' => 'present',
                    'components' => [
                        $this->mathWrittenComp->id => 60,
                        $this->mathMcqComp->id => 20,
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.store'), $payload);

        // Controller redirects back with error toast when exam is published
        $response->assertRedirect();
        $this->assertEquals(0, ExamMark::count());

        // 2. Unpublish briefly to create a baseline mark
        $this->exam->update(['is_published' => false, 'status' => 'marks_entry']);
        $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.store'), $payload);

        $mark = ExamMark::first();
        $this->assertNotNull($mark);
        $this->assertEquals(80, $mark->marks_obtained);

        // Re-publish exam
        $this->exam->update(['is_published' => true, 'status' => 'published']);

        // 3. Post audited correction
        $correctionPayload = [
            'new_marks' => 85,
            'reason' => 'Re-scrutiny requested by student; Written re-evaluated from 60 to 65.',
        ];

        $correctionResponse = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.marks.correct', $mark->id), $correctionPayload);

        $correctionResponse->assertRedirect();

        // Verify mark updated
        $mark->refresh();
        $this->assertEquals(85, $mark->marks_obtained);

        // Verify audit history created
        $history = ExamMarkHistory::where('exam_mark_id', $mark->id)->first();
        $this->assertNotNull($history);
        $this->assertEquals(80, $history->previous_marks);
        $this->assertEquals(85, $history->new_marks);
        $this->assertStringContainsString('Re-scrutiny', $history->reason);
    }

    /**
     * 6.4: Validate report card rendering in HTML preview and PDF generation.
     */
    public function test_report_card_html_preview_and_pdf_generation(): void
    {
        // Create sample mark and calculated result
        $gradingService = app(GradingService::class);
        $tier = $gradingService->calculateGradeTier($this->scale, 85);

        ExamMark::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'exam_subject_id' => $this->mathExamSubj->id,
            'student_id' => $this->student1->id,
            'marks_obtained' => 85,
            'percentage' => 85,
            'grade_tier_id' => $tier->id,
            'attendance_status' => 'present',
            'is_passed' => true,
        ]);

        ExamResult::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_year_id' => $this->year->id,
            'student_id' => $this->student1->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'total_marks_obtained' => 85,
            'percentage' => 85,
            'gpa' => 5.00,
            'overall_grade' => 'A+',
            'result_status' => 'passed',
            'rank_in_class' => 1,
            'rank_in_section' => 1,
        ]);

        // 1. HTML preview
        $previewResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.report-cards.show', [$this->exam->id, $this->student1->id]));

        $previewResponse->assertOk();
        $previewResponse->assertSee('Farhana Akter');
        $previewResponse->assertSee('SID-2026-001');
        $previewResponse->assertSee('SUBJECT-WISE GRADE & MARK SHEET', false);
        $previewResponse->assertSee('Joypurhat Government Girls High School');

        // 2. Single Student PDF download
        $pdfResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.report-cards.pdf', [$this->exam->id, $this->student1->id]));

        $pdfResponse->assertOk();
        $this->assertEquals('application/pdf', $pdfResponse->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $pdfResponse->getContent());

        // 3. Bulk PDF generation for class
        $bulkPdfResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.report-cards.bulk-pdf', [
                'exam' => $this->exam->id,
                'academic_class_id' => $this->class->id,
            ]));

        $bulkPdfResponse->assertOk();
        $this->assertEquals('application/pdf', $bulkPdfResponse->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $bulkPdfResponse->getContent());
    }

    /**
     * Verify that visiting /admin/exams/results renders the tabulation view without route conflict.
     */
    public function test_results_tabulation_page_renders_without_route_shadowing(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.results.index'));

        $response->assertOk();
        $response->assertViewIs('admin.exams.results.index');
        $response->assertSee('Tabulation');
    }

    /**
     * Verify that visiting an exam show route redirects cleanly to subjects configuration.
     */
    public function test_exam_show_redirects_to_subjects_index(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.show', $this->exam));

        $response->assertRedirect(route('admin.exams.subjects.index', $this->exam));
    }

    /**
     * Verify that marksheet padding settings can be updated and are rendered in the marksheet.
     */
    public function test_admin_can_update_marksheet_padding_settings(): void
    {
        $postData = [
            'headmaster_title' => 'Head Master',
            'header_title' => 'Test Model School',
            'sub_header_title' => 'Annual Exam 2026',
            'show_highest_marks' => '1',
            'show_attendance_report' => '1',
            'show_extra_activities' => '1',
            'show_gpa_grading_scale' => '1',
            'padding_top' => '25mm',
            'padding_right' => '15mm',
            'padding_bottom' => '20mm',
            'padding_left' => '18mm',
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.settings.marksheet.update'), $postData);

        $response->assertRedirect(route('admin.exams.settings.marksheet'));

        $this->assertDatabaseHas('marksheet_settings', [
            'school_id' => $this->school->id,
            'padding_top' => '25mm',
            'padding_right' => '15mm',
            'padding_bottom' => '20mm',
            'padding_left' => '18mm',
        ]);

        $marksheetResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.report-cards.show', [$this->exam->id, $this->student1->id]));

        $marksheetResponse->assertOk();
        $marksheetResponse->assertSee('padding: 25mm 15mm 20mm 18mm;', false);
        $marksheetResponse->assertSee('CO-CURRICULAR ACTIVITIES');
        $marksheetResponse->assertSee('ATTENDANCE REPORT');
        $marksheetResponse->assertSee('GPA GRADING');

        // Test toggling tables off
        $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.exams.settings.marksheet.update'), [
                'headmaster_title' => 'Head Master',
                'show_highest_marks' => '0',
                'show_attendance_report' => '0',
                'show_extra_activities' => '0',
                'show_gpa_grading_scale' => '0',
                'padding_top' => '10mm',
                'padding_right' => '12mm',
                'padding_bottom' => '10mm',
                'padding_left' => '12mm',
            ]);

        $toggledResponse = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.report-cards.show', [$this->exam->id, $this->student1->id]));

        $toggledResponse->assertDontSee('CO-CURRICULAR ACTIVITIES');
        $toggledResponse->assertDontSee('ATTENDANCE REPORT');
        $toggledResponse->assertDontSee('GPA GRADING');
    }

    /**
     * Rule 1 Test: Failing in an optional subject does not trigger fail_any_fails_all overall failure.
     */
    public function test_optional_subject_failure_does_not_fail_student_overall(): void
    {
        $optSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Higher Mathematics',
            'code' => '126',
            'type' => 'theory',
        ]);

        $optExamSubj = ExamSubject::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'subject_id' => $optSubject->id,
            'full_marks' => 100,
            'pass_marks' => 33,
            'order_index' => 3,
        ]);

        // Designate optSubject as student1's optional subject
        $enrollment = StudentEnrollment::where('student_id', $this->student1->id)->first();
        $enrollment->update(['optional_subject_id' => $optSubject->id]);

        $tierA = GradeTier::where('grading_scale_id', $this->scale->id)->where('grade', 'A')->first();
        $tierF = GradeTier::where('grading_scale_id', $this->scale->id)->where('grade', 'F')->first();

        // Student passes Math with 75 (A, GP 4.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $this->mathExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 75,
                'percentage' => 75,
                'grade_tier_id' => $tierA->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // Student passes English with 70 (A, GP 4.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $this->engExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 70,
                'percentage' => 70,
                'grade_tier_id' => $tierA->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // Student FAILS Higher Math (Optional) with 20 marks (F, GP 0.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $optExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 20,
                'percentage' => 20,
                'grade_tier_id' => $tierF->id,
                'attendance_status' => 'present',
                'is_passed' => false,
            ]
        );

        // Calculate results with fail_any_fails_all = true
        $this->scale->update(['fail_any_fails_all' => true]);
        $service = app(ExamResultService::class);
        $service->calculateExamResults($this->exam, $this->class->id);

        $res = ExamResult::where('exam_id', $this->exam->id)->where('student_id', $this->student1->id)->first();

        $this->assertNotNull($res);
        // Student should PASS overall because failure was in optional subject
        $this->assertEquals('passed', $res->result_status);
        $this->assertEquals(4.00, (float) $res->gpa);
        $this->assertEquals('A', $res->overall_grade);
        $this->assertEquals(1, $res->failed_subjects_count);
    }

    /**
     * Rule 2 Test: Optional subject points above 2.00 contribute bonus GP and boost to GPA 5.00 (A+).
     */
    public function test_optional_subject_bonus_points_boost_gpa_to_five_point_zero_and_grade_a_plus(): void
    {
        $bioSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Biology',
            'code' => '138',
            'type' => 'theory',
        ]);

        $bioExamSubj = ExamSubject::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'subject_id' => $bioSubject->id,
            'full_marks' => 100,
            'pass_marks' => 33,
            'order_index' => 4,
        ]);

        $enrollment = StudentEnrollment::where('student_id', $this->student1->id)->first();
        $enrollment->update(['optional_subject_id' => $bioSubject->id]);

        $tierA = GradeTier::where('grading_scale_id', $this->scale->id)->where('grade', 'A')->first();
        $tierAPlus = GradeTier::where('grading_scale_id', $this->scale->id)->where('grade', 'A+')->first();

        // Math: 75 marks (A, GP 4.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $this->mathExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 75,
                'percentage' => 75,
                'grade_tier_id' => $tierA->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // English: 85 marks (A+, GP 5.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $this->engExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 85,
                'percentage' => 85,
                'grade_tier_id' => $tierAPlus->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // Biology (Optional): 85 marks (A+, GP 5.00). Bonus = 5.00 - 2.00 = +3.00!
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $bioExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 85,
                'percentage' => 85,
                'grade_tier_id' => $tierAPlus->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // Total points = (4.00 + 5.00) + 3.00 = 12.00
        // Compulsory divisor = 2. GPA = min(5.00, 12 / 2) = 5.00!
        $service = app(ExamResultService::class);
        $service->calculateExamResults($this->exam, $this->class->id);

        $res = ExamResult::where('exam_id', $this->exam->id)->where('student_id', $this->student1->id)->first();

        $this->assertEquals(5.00, (float) $res->gpa);
        $this->assertEquals('A+', $res->overall_grade);
        $this->assertEquals('passed', $res->result_status);
    }

    /**
     * Test optional subject with Grade C or D contributes 0 bonus points.
     */
    public function test_optional_subject_with_c_or_d_grade_adds_zero_bonus_points(): void
    {
        $agriSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Agriculture Studies',
            'code' => '134',
            'type' => 'theory',
        ]);

        $agriExamSubj = ExamSubject::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'subject_id' => $agriSubject->id,
            'full_marks' => 100,
            'pass_marks' => 33,
            'order_index' => 5,
        ]);

        $enrollment = StudentEnrollment::where('student_id', $this->student1->id)->first();
        $enrollment->update(['optional_subject_id' => $agriSubject->id]);

        $tierA = GradeTier::where('grading_scale_id', $this->scale->id)->where('grade', 'A')->first();
        $tierC = GradeTier::where('grading_scale_id', $this->scale->id)->where('grade', 'C')->first();

        // Math: 70 (A, 4.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $this->mathExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 70,
                'percentage' => 70,
                'grade_tier_id' => $tierA->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // English: 70 (A, 4.00)
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $this->engExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 70,
                'percentage' => 70,
                'grade_tier_id' => $tierA->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        // Agriculture (Optional): 45 marks (C, GP 2.00). Bonus = max(0, 2.00 - 2.00) = 0.00!
        ExamMark::updateOrCreate(
            ['exam_subject_id' => $agriExamSubj->id, 'student_id' => $this->student1->id],
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'academic_class_id' => $this->class->id,
                'section_id' => $this->section->id,
                'marks_obtained' => 45,
                'percentage' => 45,
                'grade_tier_id' => $tierC->id,
                'attendance_status' => 'present',
                'is_passed' => true,
            ]
        );

        $service = app(ExamResultService::class);
        $service->calculateExamResults($this->exam, $this->class->id);

        $res = ExamResult::where('exam_id', $this->exam->id)->where('student_id', $this->student1->id)->first();

        // GPA: (4.00 + 4.00 + 0) / 2 = 4.00
        $this->assertEquals(4.00, (float) $res->gpa);
        $this->assertEquals('A', $res->overall_grade);
    }

    /**
     * Test batch assigning optional subject to class cohort.
     */
    public function test_batch_assign_optional_subject_to_class_cohort(): void
    {
        $optSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Computer Science',
            'code' => '142',
            'type' => 'theory',
        ]);

        $payload = [
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => null,
            'optional_subject_id' => $optSubject->id,
            'overwrite_existing' => '1',
        ];

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.students.batch-assign-optional-subject'), $payload);

        $response->assertRedirect();

        $this->assertDatabaseHas('student_enrollments', [
            'student_id' => $this->student1->id,
            'optional_subject_id' => $optSubject->id,
        ]);

        $this->assertDatabaseHas('student_enrollments', [
            'student_id' => $this->student2->id,
            'optional_subject_id' => $optSubject->id,
        ]);
    }

    /**
     * Test marksheet card renders (Optional) tag next to 4th subject.
     */
    public function test_marksheet_renders_optional_badge_on_4th_subject(): void
    {
        $optSubject = Subject::create([
            'school_id' => $this->school->id,
            'name' => 'Islamic Studies',
            'code' => '111',
            'type' => 'theory',
        ]);

        ExamSubject::create([
            'school_id' => $this->school->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class->id,
            'subject_id' => $optSubject->id,
            'full_marks' => 100,
            'pass_marks' => 33,
            'order_index' => 6,
        ]);

        $enrollment = StudentEnrollment::where('student_id', $this->student1->id)->first();
        $enrollment->update(['optional_subject_id' => $optSubject->id]);

        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.exams.report-cards.show', [$this->exam->id, $this->student1->id]));

        $response->assertOk();
        $response->assertSee('(Optional)');
        $response->assertSee('ISLAMIC STUDIES');
    }
}
