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
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('academic_group_id')->nullable()->constrained('academic_groups')->nullOnDelete();
            $table->string('roll_number', 30)->nullable();
            $table->string('status', 24)->default('current'); // current, promoted, transferred, graduated, repeated
            $table->date('enrolled_at')->nullable();
            $table->date('promoted_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Institutional & academic lookup performance indexes
            $table->index(['school_id', 'student_id', 'status'], 'enrollments_student_status_idx');
            $table->index(['school_id', 'academic_year_id', 'academic_class_id', 'section_id'], 'enrollments_session_class_sec_idx');
            $table->index(['school_id', 'roll_number'], 'enrollments_roll_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
