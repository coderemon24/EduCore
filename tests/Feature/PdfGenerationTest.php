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
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\LaravelPdf\Facades\Pdf;
use Tests\TestCase;

class PdfGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected Admin $superAdmin;

    protected FeeInvoice $invoice;

    protected FeePayment $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Legacy High School',
            'code' => 'LHS-01',
            'status' => 'active',
            'currency' => 'USD',
        ]);

        app()->instance('current_school_id', $this->school->id);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        $year = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026-2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
        ]);

        $class = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'numeric_order' => 10,
        ]);

        $student = Student::create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-2026-999',
            'admission_date' => '2026-01-10',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '2010-05-15',
            'gender' => 'male',
            'enrollment_status' => 'active',
        ]);

        $category = FeeCategory::create([
            'school_id' => $this->school->id,
            'name' => 'Tuition Fee',
            'code' => 'TUI',
            'is_active' => true,
        ]);

        $this->invoice = FeeInvoice::create([
            'school_id' => $this->school->id,
            'invoice_number' => 'INV-2026-99999',
            'student_id' => $student->id,
            'academic_year_id' => $year->id,
            'academic_class_id' => $class->id,
            'title' => 'Term 1 Tuition',
            'issue_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(15),
            'subtotal' => 500.00,
            'discount_amount' => 50.00,
            'paid_amount' => 200.00,
            'due_amount' => 250.00,
            'status' => 'partial',
        ]);

        FeeInvoiceItem::create([
            'fee_invoice_id' => $this->invoice->id,
            'fee_category_id' => $category->id,
            'name' => 'Standard Tuition',
            'amount' => 500.00,
        ]);

        $this->payment = FeePayment::create([
            'school_id' => $this->school->id,
            'fee_invoice_id' => $this->invoice->id,
            'student_id' => $student->id,
            'receipt_number' => 'REC-2026-99999',
            'idempotency_token' => Str::uuid(),
            'payment_date' => Carbon::today(),
            'amount' => 200.00,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
    }

    public function test_default_pdf_driver_is_configured_to_dompdf(): void
    {
        $this->assertEquals('dompdf', config('laravel-pdf.driver'));
    }

    public function test_spatie_pdf_generates_valid_pdf_without_external_binaries(): void
    {
        $pdfBuilder = Pdf::html('<h1>Pure PHP PDF Test</h1><p>Running on Dompdf driver</p>')
            ->format('a4');

        $content = $pdfBuilder->generatePdfContent();

        $this->assertStringStartsWith('%PDF-', $content);
        $this->assertGreaterThan(500, strlen($content));
    }

    public function test_can_download_invoice_pdf(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.invoices.pdf', $this->invoice));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_can_download_receipt_pdf(): void
    {
        $response = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.receipts.pdf', $this->payment));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }
}
