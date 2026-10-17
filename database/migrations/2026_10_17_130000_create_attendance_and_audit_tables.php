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
        // 1. Student Attendance Ledger
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'excused'])->default('present');
            $table->string('remarks')->nullable();
            $table->foreignId('recorded_by_id')->nullable()->index();
            $table->foreignId('updated_by_id')->nullable()->index();
            $table->timestamps();

            // Strict Unique Constraint: One record per student per date in a school
            $table->unique(['school_id', 'student_id', 'attendance_date'], 'student_daily_att_unique');

            // High Performance Query Indexes
            $table->index(['school_id', 'section_id', 'attendance_date'], 'idx_student_att_section_date');
            $table->index(['school_id', 'academic_class_id', 'attendance_date'], 'idx_student_att_class_date');
            $table->index(['school_id', 'student_id', 'attendance_date', 'status'], 'idx_student_att_metrics');
        });

        // 2. Staff & Faculty Attendance Ledger
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'on_leave'])->default('present');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('remarks')->nullable();
            $table->foreignId('recorded_by_id')->nullable()->index();
            $table->foreignId('updated_by_id')->nullable()->index();
            $table->timestamps();

            // Unique Constraint: One record per staff member per date in a school
            $table->unique(['school_id', 'staff_id', 'attendance_date'], 'staff_daily_att_unique');

            // Performance Indexes
            $table->index(['school_id', 'attendance_date', 'status'], 'idx_staff_att_date_status');
            $table->index(['school_id', 'staff_id', 'attendance_date'], 'idx_staff_att_member_date');
        });

        // 3. Attendance Audit Logs (Correction History)
        Schema::create('attendance_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('attendable_type', 100);
            $table->unsignedBigInteger('attendable_id');
            $table->enum('action', ['created', 'updated'])->default('updated');
            $table->string('old_status', 50)->nullable();
            $table->string('new_status', 50);
            $table->string('reason')->nullable();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['school_id', 'attendable_type', 'attendable_id'], 'idx_att_audit_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_audit_logs');
        Schema::dropIfExists('staff_attendances');
        Schema::dropIfExists('student_attendances');
    }
};
