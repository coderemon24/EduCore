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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->decimal('total_full_marks', 7, 2)->default(0.00);
            $table->decimal('total_marks_obtained', 7, 2)->default(0.00);
            $table->decimal('percentage', 5, 2)->default(0.00);
            $table->decimal('gpa', 4, 2)->default(0.00);
            $table->string('overall_grade', 10)->default('F');
            $table->string('result_status', 20)->default('pending'); // passed, failed, withheld, exempted, pending
            $table->unsignedInteger('failed_subjects_count')->default(0);
            $table->unsignedInteger('rank_in_class')->nullable();
            $table->unsignedInteger('rank_in_section')->nullable();
            $table->unsignedInteger('attendance_present_days')->default(0);
            $table->unsignedInteger('attendance_total_days')->default(0);
            $table->json('extra_activities')->nullable();
            $table->string('status', 20)->default('draft'); // draft, approved, published
            $table->timestamp('calculated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['exam_id', 'student_id'], 'exam_results_exam_student_unique');
            $table->index(['school_id', 'exam_id', 'academic_class_id', 'section_id'], 'exam_results_lookup_idx');
            $table->index(['exam_id', 'result_status', 'gpa', 'total_marks_obtained'], 'exam_results_rank_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
