<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('receipt_number', 50);
            $table->foreignId('fee_invoice_id')->constrained('fee_invoices')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('payment_method', 30)->default('cash'); // cash, bank_transfer, cheque, pos_card, other
            $table->string('transaction_reference', 100)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('cheque_number', 50)->nullable();
            $table->string('idempotency_token', 64);
            $table->string('status', 24)->default('completed'); // completed, reversed
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reversed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reversed_at')->nullable();
            $table->text('reversal_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'receipt_number'], 'fee_payments_school_receipt_num_unique');
            $table->unique(['school_id', 'idempotency_token'], 'fee_payments_school_idempotency_unique');
            $table->index(['school_id', 'fee_invoice_id'], 'fee_payments_school_invoice_idx');
            $table->index(['school_id', 'student_id'], 'fee_payments_school_student_idx');
            $table->index(['school_id', 'payment_date', 'status'], 'fee_payments_school_date_status_idx');
            $table->index(['school_id', 'payment_method'], 'fee_payments_school_method_idx');
        });

        Schema::create('fee_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('fee_invoice_id')->constrained('fee_invoices')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('type', 20)->default('fixed'); // fixed, percentage
            $table->decimal('rate', 8, 2)->nullable();
            $table->decimal('amount', 12, 2);
            $table->text('reason')->nullable();
            $table->foreignId('authorized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Institutional & relational performance indexes
            $table->index(['school_id', 'fee_invoice_id'], 'fee_discounts_school_invoice_idx');
            $table->index(['school_id', 'student_id'], 'fee_discounts_school_student_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_discounts');
        Schema::dropIfExists('fee_payments');
    }
};
