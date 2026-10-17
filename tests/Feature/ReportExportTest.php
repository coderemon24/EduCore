<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\FeeCategory;
use App\Models\FeeInvoice;
use App\Models\FeeInvoiceItem;
use App\Models\FeePayment;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentEnrollment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected Admin $superAdmin;

    protected AcademicYear $year;

    protected AcademicClass $class;

    protected Section $section;

    protected Student $student;

    protected FeeInvoice $invoice;

    protected FeePayment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Report Testing Academy',
            'code' => 'RTA-01',
            'status' => 'active',
            'currency' => 'USD',
        ]);

        app()->instance('current_school_id', $this->school->id);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        $this->year = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026-2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
        ]);

        $this->class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 8',
            'numeric_order' => 8,
        ]);

        $this->section = Section::create([
            'school_id' => $this->school->id,
            'academic_class_id' => $this->class->id,
            'name' => 'Section A',
            'capacity' => 40,
        ]);

        $this->student = Student::create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-RPT-001',
            'admission_date' => '2026-01-10',
            'first_name' => 'Michael',
            'last_name' => 'Corleone',
            'date_of_birth' => '2012-04-10',
            'gender' => 'male',
            'enrollment_status' => 'active',
        ]);

        StudentEnrollment::create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'roll_number' => '101',
            'status' => 'current',
        ]);

        $category = FeeCategory::create([
            'school_id' => $this->school->id,
            'name' => 'Tuition Fee',
            'code' => 'TUI',
            'is_active' => true,
        ]);

        $this->invoice = FeeInvoice::create([
            'school_id' => $this->school->id,
            'invoice_number' => 'INV-RPT-0001',
            'student_id' => $this->student->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'title' => 'Term 1 Tuition',
            'issue_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(15),
            'subtotal' => 600.00,
            'discount_amount' => 50.00,
            'paid_amount' => 300.00,
            'due_amount' => 250.00,
            'status' => 'partial',
        ]);

        FeeInvoiceItem::create([
            'fee_invoice_id' => $this->invoice->id,
            'fee_category_id' => $category->id,
            'name' => 'Standard Tuition',
            'amount' => 600.00,
        ]);

        $this->payment = FeePayment::create([
            'school_id' => $this->school->id,
            'fee_invoice_id' => $this->invoice->id,
            'student_id' => $this->student->id,
            'receipt_number' => 'REC-RPT-0001',
            'idempotency_token' => (string) Str::uuid(),
            'payment_date' => Carbon::today(),
            'amount' => 300.00,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        StudentAttendance::create([
            'school_id' => $this->school->id,
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'student_id' => $this->student->id,
            'attendance_date' => Carbon::today()->toDateString(),
            'status' => 'present',
        ]);
    }

    public function test_can_export_fee_collections_as_csv(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.reports.export', [
                'tab' => 'collections',
                'format' => 'csv',
                'start_date' => Carbon::today()->startOfMonth()->toDateString(),
                'end_date' => Carbon::today()->toDateString(),
            ]));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
    }

    public function test_can_export_fee_collections_as_excel(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.reports.export', [
                'tab' => 'collections',
                'format' => 'excel',
                'start_date' => Carbon::today()->startOfMonth()->toDateString(),
                'end_date' => Carbon::today()->toDateString(),
            ]));

        $response->assertOk();
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
    }

    public function test_can_export_fee_collections_as_pdf(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.reports.export', [
                'tab' => 'collections',
                'format' => 'pdf',
                'start_date' => Carbon::today()->startOfMonth()->toDateString(),
                'end_date' => Carbon::today()->toDateString(),
            ]));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_can_export_fee_dues_as_pdf_and_excel(): void
    {
        $responsePdf = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.reports.export', [
                'tab' => 'dues',
                'format' => 'pdf',
            ]));
        $responsePdf->assertOk();
        $this->assertStringContainsString('application/pdf', $responsePdf->headers->get('Content-Type'));

        $responseExcel = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.reports.export', [
                'tab' => 'dues',
                'format' => 'excel',
            ]));
        $responseExcel->assertOk();
        $this->assertStringContainsString('application/vnd.ms-excel', $responseExcel->headers->get('Content-Type'));
    }

    public function test_can_export_attendance_report_as_csv_excel_and_pdf(): void
    {
        $params = [
            'academic_year_id' => $this->year->id,
            'academic_class_id' => $this->class->id,
            'section_id' => $this->section->id,
            'month' => Carbon::today()->month,
            'year' => Carbon::today()->year,
        ];

        // CSV
        $responseCsv = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.attendance.reports.export', array_merge($params, ['format' => 'csv'])));
        $responseCsv->assertOk();
        $this->assertStringContainsString('text/csv', $responseCsv->headers->get('Content-Type'));

        // Excel
        $responseExcel = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.attendance.reports.export', array_merge($params, ['format' => 'excel'])));
        $responseExcel->assertOk();
        $this->assertStringContainsString('application/vnd.ms-excel', $responseExcel->headers->get('Content-Type'));

        // PDF
        $responsePdf = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.attendance.reports.export', array_merge($params, ['format' => 'pdf'])));
        $responsePdf->assertOk();
        $this->assertStringContainsString('application/pdf', $responsePdf->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $responsePdf->getContent());
    }
}
