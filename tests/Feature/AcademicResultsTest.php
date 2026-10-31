<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamResult;
use App\Models\ExamSubject;
use App\Models\GradeTier;
use App\Models\GradingScale;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use Database\Seeders\AcademicSeeder;
use Database\Seeders\ExamSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SchoolSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\StudentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AcademicResultsTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;
    protected AcademicYear $year;
    protected AcademicClass $class6;
    protected Section $sectionA;
    protected Exam $exam;
    protected Student $student1;
    protected Student $student2;
    protected ExamResult $result1;
    protected ExamResult $result2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            SchoolSeeder::class,
            RolePermissionSeeder::class,
            StaffSeeder::class,
            AcademicSeeder::class,
            StudentSeeder::class,
            ExamSeeder::class,
        ]);

        $this->school = School::first();
        $this->year = AcademicYear::withoutGlobalScopes()->where('school_id', $this->school->id)->where('is_current', true)->first();
        $this->class6 = AcademicClass::withoutGlobalScopes()->where('school_id', $this->school->id)->where('numeric_code', 6)->first();
        $this->sectionA = Section::withoutGlobalScopes()->where('academic_class_id', $this->class6->id)->first();
        $this->exam = Exam::withoutGlobalScopes()->where('school_id', $this->school->id)->first();

        // Retrieve seeded students or create test students
        $students = Student::withoutGlobalScopes()->where('school_id', $this->school->id)->take(2)->get();
        if ($students->count() < 2) {
            $this->student1 = Student::create([
                'school_id' => $this->school->id,
                'first_name' => 'Tanzila',
                'last_name' => 'Akter',
                'admission_number' => 'ADM-2026-001',
                'gender' => 'female',
                'status' => 'active',
            ]);
            $this->student2 = Student::create([
                'school_id' => $this->school->id,
                'first_name' => 'Farhan',
                'last_name' => 'Ahmed',
                'admission_number' => 'ADM-2026-002',
                'gender' => 'male',
                'status' => 'active',
            ]);
            StudentEnrollment::create([
                'school_id' => $this->school->id,
                'student_id' => $this->student1->id,
                'academic_class_id' => $this->class6->id,
                'section_id' => $this->sectionA->id,
                'academic_year_id' => $this->year->id,
                'roll_number' => '01',
                'status' => 'current',
            ]);
            StudentEnrollment::create([
                'school_id' => $this->school->id,
                'student_id' => $this->student2->id,
                'academic_class_id' => $this->class6->id,
                'section_id' => $this->sectionA->id,
                'academic_year_id' => $this->year->id,
                'roll_number' => '02',
                'status' => 'current',
            ]);
        } else {
            $this->student1 = $students[0];
            $this->student2 = $students[1];
        }

        // Ensure ExamResults exist for test assertions
        $this->result1 = ExamResult::updateOrCreate(
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'student_id' => $this->student1->id,
            ],
            [
                'academic_year_id' => $this->year->id,
                'academic_class_id' => $this->class6->id,
                'section_id' => $this->sectionA->id,
                'gpa' => 5.00,
                'grade' => 'A+',
                'total_marks_obtained' => 850.00,
                'total_marks_possible' => 1000.00,
                'percentage' => 85.00,
                'rank_in_class' => 1,
                'rank_in_section' => 1,
                'status' => 'pass',
            ]
        );

        $this->result2 = ExamResult::updateOrCreate(
            [
                'school_id' => $this->school->id,
                'exam_id' => $this->exam->id,
                'student_id' => $this->student2->id,
            ],
            [
                'academic_year_id' => $this->year->id,
                'academic_class_id' => $this->class6->id,
                'section_id' => $this->sectionA->id,
                'gpa' => 4.50,
                'grade' => 'A',
                'total_marks_obtained' => 780.00,
                'total_marks_possible' => 1000.00,
                'percentage' => 78.00,
                'rank_in_class' => 2,
                'rank_in_section' => 2,
                'status' => 'pass',
            ]
        );
    }

    public function test_results_page_renders_successfully_with_status_200(): void
    {
        $response = $this->get(route('frontend.academic.results'));

        $response->assertStatus(200);
        $response->assertViewIs('frontend.academic.results');
        $response->assertViewHas('exams');
        $response->assertViewHas('academicClasses');
        $response->assertViewHas('results');
        $response->assertViewHas('telemetry');
        $response->assertSee('একাডেমিক ফলাফল');
        $response->assertSee('মেধা তালিকা');
    }

    public function test_results_page_filters_by_exam_and_class(): void
    {
        $response = $this->get(route('frontend.academic.results', [
            'year_id' => $this->year->id,
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class6->id,
            'section_id' => $this->sectionA->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee($this->student1->first_name);
        $response->assertSee('5.00');
        $response->assertSee('A+');
    }

    public function test_search_student_by_roll_resolves_individual_marksheet(): void
    {
        $enrollment = $this->student1->currentEnrollment;
        $roll = $enrollment?->roll_number ?? '01';

        $response = $this->get(route('frontend.academic.results', [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class6->id,
            'roll_or_id' => $roll,
            'tab' => 'marksheet',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('individualMarksheet');
        $response->assertSee($this->student1->first_name);
        $response->assertSee('ডিজিটাল নম্বরপত্র');
    }

    public function test_invalid_roll_search_does_not_crash_and_returns_empty_marksheet(): void
    {
        $response = $this->get(route('frontend.academic.results', [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class6->id,
            'roll_or_id' => '99999999',
            'tab' => 'marksheet',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('individualMarksheet', null);
        $response->assertSee('ব্যক্তিগত নম্বরপত্র অনুসন্ধান');
    }

    public function test_telemetry_metrics_calculation_accuracy(): void
    {
        $response = $this->get(route('frontend.academic.results', [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class6->id,
        ]));

        $response->assertStatus(200);
        $telemetry = $response->viewData('telemetry');

        $this->assertIsArray($telemetry);
        $this->assertGreaterThanOrEqual(2, $telemetry['appeared']);
        $this->assertGreaterThanOrEqual(2, $telemetry['passed']);
        $this->assertEquals(0, $telemetry['failed']);
        $this->assertEquals(100.0, $telemetry['pass_rate']);
        $this->assertGreaterThanOrEqual(1, $telemetry['gpa5_count']);
    }

    public function test_multi_tenant_isolation_prevents_cross_school_results_leak(): void
    {
        // Create second tenant school with its own result
        $schoolB = School::create([
            'name' => 'Secondary High School B',
            'slug' => 'secondary-high-school-b',
            'status' => 'active',
        ]);
        $studentB = Student::create([
            'school_id' => $schoolB->id,
            'first_name' => 'SecretStudentB',
            'last_name' => 'Private',
            'admission_number' => 'SECRET-B-001',
            'gender' => 'male',
            'admission_date' => now()->toDateString(),
            'status' => 'active',
        ]);
        ExamResult::create([
            'school_id' => $schoolB->id,
            'academic_year_id' => $this->year->id,
            'exam_id' => $this->exam->id,
            'student_id' => $studentB->id,
            'academic_class_id' => $this->class6->id,
            'section_id' => $this->sectionA->id,
            'gpa' => 3.00,
            'grade' => 'B',
            'total_marks_obtained' => 500.00,
            'total_marks_possible' => 1000.00,
            'percentage' => 50.00,
            'rank_in_class' => 10,
            'rank_in_section' => 5,
            'status' => 'pass',
        ]);

        $response = $this->get(route('frontend.academic.results', [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class6->id,
        ]));

        $response->assertStatus(200);
        $response->assertDontSee('SecretStudentB');
        $response->assertDontSee('SECRET-B-001');
    }

    public function test_zero_n_plus_one_queries_on_results_page(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->get(route('frontend.academic.results', [
            'exam_id' => $this->exam->id,
            'academic_class_id' => $this->class6->id,
        ]));

        $response->assertStatus(200);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Total queries should be bounded (bounded schema checks, layout notices, and eagerly loaded results)
        // Strictly under 25 queries regardless of student count (verifying zero N+1 query leaks)
        $this->assertLessThan(25, count($queries), "Query count was " . count($queries) . ", which indicates possible N+1 query leak.");
    }
}
