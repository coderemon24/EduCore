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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('staff_code', 32);
            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('gender', 20);
            $table->date('date_of_birth')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 32)->nullable();
            $table->boolean('is_teaching')->default(true)->index();
            $table->string('department', 100)->nullable()->index();
            $table->string('designation', 100);
            $table->string('qualification', 255)->nullable();
            $table->unsignedSmallInteger('experience_years')->nullable();
            $table->date('joining_date');
            $table->decimal('basic_salary', 10, 2)->nullable();
            $table->string('contract_type', 30)->default('permanent');
            $table->string('status', 24)->default('active')->index();
            $table->string('photo')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['school_id', 'staff_code'], 'staff_school_code_unique');
            $table->index(['school_id', 'status'], 'staff_school_status_idx');
            $table->index(['school_id', 'is_teaching'], 'staff_school_teaching_idx');
            $table->index(['school_id', 'first_name', 'last_name'], 'staff_school_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
