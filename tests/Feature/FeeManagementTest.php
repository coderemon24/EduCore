<?php

namespace Tests\Feature;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\FeeCategory;
use App\Models\FeeInvoice;
use App\Models\FeePayment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use App\Services\FeePaymentService;
use App\Services\InvoiceGenerationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FeeManagementTest extends TestCase
{
    use RefreshDatabase;

    protected School $school;

    protected School $otherSchool;

    protected Admin $superAdmin;

    protected User $accountant;

    protected User $unauthorizedUser;

    protected AcademicYear $academicYear;

    protected AcademicClass $academicClass;

    protected Student $student;

    protected FeeCategory $tuitionCategory;

    protected FeeCategory $examCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Greenfield Academy',
            'code' => 'GRN-01',
            'status' => 'active',
            'currency' => 'USD',
        ]);

        $this->otherSchool = School::create([
            'name' => 'Silvercreek High',
            'code' => 'SLV-02',
            'status' => 'active',
            'currency' => 'USD',
        ]);

        app()->instance('current_school_id', $this->school->id);

        $this->superAdmin = Admin::factory()->create([
            'role' => 'super_admin',
        ]);

        // Permissions
        $permissions = [
            'fees.view',
            'fees.structure.manage',
            'fees.invoice.create',
            'fees.collect',
            'fees.discount',
            'fees.reverse',
            'fees.reports',
        ];

        $permModels = [];
        foreach ($permissions as $slug) {
            $permModels[] = Permission::create([
                'name' => ucfirst($slug),
                'slug' => $slug,
                'group' => 'fees',
            ]);
        }

        $accountantRole = Role::create([
            'school_id' => $this->school->id,
            'name' => 'School Accountant',
            'slug' => 'school_accountant',
        ]);
        $accountantRole->permissions()->sync(collect($permModels)->pluck('id'));

        $this->accountant = User::factory()->create([
            'school_id' => $this->school->id,
            'status' => 'active',
        ]);
        $this->accountant->roles()->attach($accountantRole->id);

        $this->unauthorizedUser = User::factory()->create([
            'school_id' => $this->school->id,
            'status' => 'active',
        ]);

        $this->academicYear = AcademicYear::create([
            'school_id' => $this->school->id,
            'name' => '2026-2027',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_current' => true,
        ]);

        $this->academicClass = AcademicClass::create([
            'school_id' => $this->school->id,
            'name' => 'Grade 10',
            'numeric_code' => 10,
        ]);

        $this->student = Student::create([
            'school_id' => $this->school->id,
            'admission_number' => 'ADM-2026-0001',
            'admission_date' => '2026-01-10',
            'first_name' => 'Daniel',
            'last_name' => 'Vance',
            'gender' => 'male',
            'status' => 'active',
        ]);

        StudentEnrollment::create([
            'school_id' => $this->school->id,
            'student_id' => $this->student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'status' => 'current',
        ]);

        $this->tuitionCategory = FeeCategory::create([
            'school_id' => $this->school->id,
            'name' => 'Tuition Fee',
            'code' => 'TUITION',
            'is_active' => true,
        ]);

        $this->examCategory = FeeCategory::create([
            'school_id' => $this->school->id,
            'name' => 'Examination Fee',
            'code' => 'EXAM',
            'is_active' => true,
        ]);
    }

    /**
     * Test exact decimal arithmetic on invoices.
     */
    public function test_exact_decimal_arithmetic_on_invoices(): void
    {
        $invoiceService = app(InvoiceGenerationService::class);

        $invoice = $invoiceService->createSingleInvoice([
            'student_id' => $this->student->id,
            'title' => 'Term 1 Fees',
            'due_date' => Carbon::today()->addDays(15)->toDateString(),
            'items' => [
                ['fee_category_id' => $this->tuitionCategory->id, 'name' => 'Tuition Fee', 'amount' => 1250.50],
                ['fee_category_id' => $this->examCategory->id, 'name' => 'Lab Fee', 'amount' => 249.50],
            ],
        ], $this->school->id, $this->accountant->id);

        $this->assertEquals(1500.00, (float) $invoice->subtotal);
        $this->assertEquals(0.00, (float) $invoice->discount_amount);
        $this->assertEquals(0.00, (float) $invoice->paid_amount);
        $this->assertEquals(1500.00, (float) $invoice->due_amount);
        $this->assertEquals('unpaid', $invoice->status);
    }

    /**
     * Test partial payment processing and status transitions.
     */
    public function test_partial_payment_and_status_transitions(): void
    {
        $invoiceService = app(InvoiceGenerationService::class);
        $paymentService = app(FeePaymentService::class);

        $invoice = $invoiceService->createSingleInvoice([
            'student_id' => $this->student->id,
            'title' => 'Monthly Tuition',
            'due_date' => Carbon::today()->addDays(10)->toDateString(),
            'items' => [
                ['fee_category_id' => $this->tuitionCategory->id, 'name' => 'Tuition Fee', 'amount' => 500.00],
            ],
        ], $this->school->id, $this->accountant->id);

        // 1. Partial Payment of $200.00
        $payment1 = $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 200.00,
            'payment_method' => 'cash',
            'idempotency_token' => (string) Str::uuid(),
        ], $this->school->id, $this->accountant->id);

        $invoice->refresh();
        $this->assertEquals(200.00, (float) $invoice->paid_amount);
        $this->assertEquals(300.00, (float) $invoice->due_amount);
        $this->assertEquals('partially_paid', $invoice->status);
        $this->assertEquals('completed', $payment1->status);

        // 2. Full Remaining Payment of $300.00
        $payment2 = $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 300.00,
            'payment_method' => 'bank_transfer',
            'transaction_reference' => 'TXN-98765',
            'idempotency_token' => (string) Str::uuid(),
        ], $this->school->id, $this->accountant->id);

        $invoice->refresh();
        $this->assertEquals(500.00, (float) $invoice->paid_amount);
        $this->assertEquals(0.00, (float) $invoice->due_amount);
        $this->assertEquals('paid', $invoice->status);
    }

    /**
     * Test overpayment rejection.
     */
    public function test_overpayment_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $invoiceService = app(InvoiceGenerationService::class);
        $paymentService = app(FeePaymentService::class);

        $invoice = $invoiceService->createSingleInvoice([
            'student_id' => $this->student->id,
            'title' => 'Tuition',
            'due_date' => Carbon::today()->addDays(5)->toDateString(),
            'items' => [
                ['fee_category_id' => $this->tuitionCategory->id, 'name' => 'Tuition', 'amount' => 100.00],
            ],
        ], $this->school->id, $this->accountant->id);

        // Attempt to pay $150.00 on a $100.00 invoice
        $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 150.00,
            'payment_method' => 'cash',
        ], $this->school->id, $this->accountant->id);
    }

    /**
     * Test idempotency token prevents accidental double payments.
     */
    public function test_idempotency_token_prevents_duplicate_payments(): void
    {
        $invoiceService = app(InvoiceGenerationService::class);
        $paymentService = app(FeePaymentService::class);

        $invoice = $invoiceService->createSingleInvoice([
            'student_id' => $this->student->id,
            'title' => 'Tuition',
            'due_date' => Carbon::today()->addDays(5)->toDateString(),
            'items' => [
                ['fee_category_id' => $this->tuitionCategory->id, 'name' => 'Tuition', 'amount' => 400.00],
            ],
        ], $this->school->id, $this->accountant->id);

        $token = (string) Str::uuid();

        // First submission
        $payment1 = $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 200.00,
            'payment_method' => 'cash',
            'idempotency_token' => $token,
        ], $this->school->id, $this->accountant->id);

        // Repeated duplicate submission with same idempotency token
        $payment2 = $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 200.00,
            'payment_method' => 'cash',
            'idempotency_token' => $token,
        ], $this->school->id, $this->accountant->id);

        $this->assertEquals($payment1->id, $payment2->id);
        $this->assertEquals(1, FeePayment::where('idempotency_token', $token)->count());

        $invoice->refresh();
        // Balance should only be deducted once ($200.00 paid, $200.00 remaining due)
        $this->assertEquals(200.00, (float) $invoice->paid_amount);
        $this->assertEquals(200.00, (float) $invoice->due_amount);
    }

    /**
     * Test payment reversal workflow without silent deletion.
     */
    public function test_payment_reversal_workflow(): void
    {
        $invoiceService = app(InvoiceGenerationService::class);
        $paymentService = app(FeePaymentService::class);

        $invoice = $invoiceService->createSingleInvoice([
            'student_id' => $this->student->id,
            'title' => 'Exam Fee',
            'due_date' => Carbon::today()->addDays(5)->toDateString(),
            'items' => [
                ['fee_category_id' => $this->examCategory->id, 'name' => 'Board Exam Fee', 'amount' => 300.00],
            ],
        ], $this->school->id, $this->accountant->id);

        $payment = $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 300.00,
            'payment_method' => 'cheque',
            'cheque_number' => 'CHQ-554433',
            'idempotency_token' => (string) Str::uuid(),
        ], $this->school->id, $this->accountant->id);

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
        $this->assertEquals(0.00, (float) $invoice->due_amount);

        // Safe Reversal (e.g. cheque bounced)
        $reversedPayment = $paymentService->reversePayment(
            $payment,
            'Cheque returned unpaid / bounced by drawer bank',
            $this->accountant->id
        );

        $this->assertEquals('reversed', $reversedPayment->status);
        $this->assertEquals($this->accountant->id, $reversedPayment->reversed_by);
        $this->assertNotNull($reversedPayment->reversed_at);
        $this->assertStringContainsString('bounced', $reversedPayment->reversal_reason);

        // Payment row MUST still exist in database for immutable audit history
        $this->assertDatabaseHas('fee_payments', [
            'id' => $payment->id,
            'status' => 'reversed',
        ]);

        // Invoice balance restored
        $invoice->refresh();
        $this->assertEquals(0.00, (float) $invoice->paid_amount);
        $this->assertEquals(300.00, (float) $invoice->due_amount);
        $this->assertEquals('unpaid', $invoice->status);
    }

    /**
     * Test applying authorized fee waivers/discounts.
     */
    public function test_fee_waiver_authorization(): void
    {
        $invoiceService = app(InvoiceGenerationService::class);
        $paymentService = app(FeePaymentService::class);

        $invoice = $invoiceService->createSingleInvoice([
            'student_id' => $this->student->id,
            'title' => 'Semester Fee',
            'due_date' => Carbon::today()->addDays(20)->toDateString(),
            'items' => [
                ['fee_category_id' => $this->tuitionCategory->id, 'name' => 'Tuition', 'amount' => 1000.00],
            ],
        ], $this->school->id, $this->accountant->id);

        // Apply $200.00 waiver
        $discount = $paymentService->applyDiscount($invoice, [
            'name' => 'Merit Scholarship Waiver',
            'amount' => 200.00,
            'reason' => 'Academic excellence scholarship award',
        ], $this->accountant->id);

        $this->assertEquals(200.00, (float) $discount->amount);

        $invoice->refresh();
        $this->assertEquals(1000.00, (float) $invoice->subtotal);
        $this->assertEquals(200.00, (float) $invoice->discount_amount);
        $this->assertEquals(800.00, (float) $invoice->due_amount);
    }

    /**
     * Test multi-tenant isolation.
     */
    public function test_multi_tenant_isolation(): void
    {
        // Create an invoice under school 1
        $invoiceSchool1 = FeeInvoice::create([
            'school_id' => $this->school->id,
            'invoice_number' => 'INV-2026-99001',
            'student_id' => $this->student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'title' => 'School 1 Invoice',
            'issue_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(10),
            'subtotal' => 100.00,
            'due_amount' => 100.00,
            'status' => 'unpaid',
        ]);

        // Switch active school context to school 2
        app()->instance('current_school_id', $this->otherSchool->id);

        // School 2 should NOT find school 1's invoice through scoped query
        $foundInvoices = FeeInvoice::all();
        $this->assertFalse($foundInvoices->contains($invoiceSchool1->id));
    }

    /**
     * Test permission enforcement on payment collection.
     */
    public function test_permission_gates_enforcement(): void
    {
        $invoice = FeeInvoice::create([
            'school_id' => $this->school->id,
            'invoice_number' => 'INV-2026-88001',
            'student_id' => $this->student->id,
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'title' => 'Test Invoice',
            'issue_date' => Carbon::today(),
            'due_date' => Carbon::today()->addDays(5),
            'subtotal' => 250.00,
            'due_amount' => 250.00,
            'status' => 'unpaid',
        ]);

        // Unauthorized user without 'fees.collect'
        $response = $this->actingAs($this->unauthorizedUser)
            ->post(route('admin.fees.payments.store'), [
                'fee_invoice_id' => $invoice->id,
                'amount' => 50.00,
                'payment_date' => date('Y-m-d'),
                'payment_method' => 'cash',
                'idempotency_token' => (string) Str::uuid(),
            ]);

        $response->assertStatus(403);

        // Authorized user with 'fees.collect'
        $authorizedResponse = $this->actingAs($this->accountant)
            ->post(route('admin.fees.payments.store'), [
                'fee_invoice_id' => $invoice->id,
                'amount' => 50.00,
                'payment_date' => date('Y-m-d'),
                'payment_method' => 'cash',
                'idempotency_token' => (string) Str::uuid(),
            ]);

        $authorizedResponse->assertRedirect();
        $this->assertDatabaseHas('fee_payments', [
            'fee_invoice_id' => $invoice->id,
            'amount' => 50.00,
        ]);
    }

    /**
     * Test fee directory and creation views render successfully.
     */
    public function test_fee_views_render_successfully(): void
    {
        // 1. Invoices Directory
        $invoicesRes = $this->actingAs($this->accountant)
            ->get(route('admin.fees.invoices.index'));
        $invoicesRes->assertStatus(200);

        // 2. Invoice Generation View
        $createRes = $this->actingAs($this->accountant)
            ->get(route('admin.fees.invoices.create'));
        $createRes->assertStatus(200);

        // 3. Fee Structures View
        $structuresRes = $this->actingAs($this->accountant)
            ->get(route('admin.fees.structures.index'));
        $structuresRes->assertStatus(200);

        // 4. Fee Categories View
        $categoriesRes = $this->actingAs($this->accountant)
            ->get(route('admin.fees.categories.index'));
        $categoriesRes->assertStatus(200);

        // 5. Financial Reports View
        $reportsRes = $this->actingAs($this->accountant)
            ->get(route('admin.fees.reports.index'));
        $reportsRes->assertStatus(200);

        // 6. Receipt View rendered by School Accountant and Super Admin
        $paymentService = app(FeePaymentService::class);
        $invoiceGenService = app(InvoiceGenerationService::class);
        $invoice = $invoiceGenService->createSingleInvoice([
            'academic_year_id' => $this->academicYear->id,
            'academic_class_id' => $this->academicClass->id,
            'student_id' => $this->student->id,
            'title' => 'Term 1 Tuition View Test',
            'issue_date' => '2026-02-01',
            'due_date' => '2026-02-15',
            'items' => [
                [
                    'fee_category_id' => $this->tuitionCategory->id,
                    'name' => 'Tuition Fee',
                    'amount' => 100.00,
                ],
            ],
        ], $this->school->id, $this->accountant->id);

        $payment = $paymentService->recordPayment([
            'fee_invoice_id' => $invoice->id,
            'amount' => 50.00,
            'payment_method' => 'cash',
        ], $this->school->id, $this->accountant->id);

        $receiptRes = $this->actingAs($this->accountant)
            ->get(route('admin.fees.receipts.show', $payment));
        $receiptRes->assertStatus(200);
        $receiptRes->assertSee($payment->receipt_number);

        // 7. Receipt View rendered by Super Admin (where currentSchool in session is null)
        $superAdminReceiptRes = $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.fees.receipts.show', $payment));
        $superAdminReceiptRes->assertStatus(200);
        $superAdminReceiptRes->assertSee($payment->receipt_number);
    }

    /**
     * Test creating fee category and fee structure works for both super admin and school accountant.
     */
    public function test_fee_category_and_structure_creation(): void
    {
        // 1. Super Admin creating a fee category without explicit tenant container binding
        $superAdminRes = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.fees.categories.store'), [
                'name' => 'Transport Fee',
                'code' => 'TRANS_01',
                'description' => 'Daily school bus transport charges',
                'is_active' => true,
            ]);

        $superAdminRes->assertRedirect(route('admin.fees.categories.index'));
        $this->assertDatabaseHas('fee_categories', [
            'code' => 'TRANS_01',
            'name' => 'Transport Fee',
        ]);

        $createdCategory = FeeCategory::where('code', 'TRANS_01')->firstOrFail();
        $this->assertNotNull($createdCategory->school_id);

        // 2. Super Admin creating a fee structure
        $structRes = $this->actingAs($this->superAdmin, 'admin')
            ->post(route('admin.fees.structures.store'), [
                'academic_year_id' => $this->academicYear->id,
                'academic_class_id' => $this->academicClass->id,
                'fee_category_id' => $createdCategory->id,
                'name' => 'Grade 10 Monthly Bus Service',
                'amount' => '45.00',
                'frequency' => 'monthly',
                'due_day' => 10,
                'is_active' => true,
            ]);

        $structRes->assertRedirect(route('admin.fees.structures.index'));
        $this->assertDatabaseHas('fee_structures', [
            'name' => 'Grade 10 Monthly Bus Service',
            'amount' => 45.00,
        ]);

        // 3. School Accountant creating a fee category
        $accountantRes = $this->actingAs($this->accountant, 'web')
            ->post(route('admin.fees.categories.store'), [
                'name' => 'Library Fee',
                'code' => 'LIB_01',
                'description' => 'Annual library subscription',
                'is_active' => true,
            ]);

        $accountantRes->assertRedirect(route('admin.fees.categories.index'));
        $this->assertDatabaseHas('fee_categories', [
            'code' => 'LIB_01',
            'school_id' => $this->school->id,
        ]);
    }
}
