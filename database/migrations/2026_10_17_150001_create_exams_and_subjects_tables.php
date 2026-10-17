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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('grading_scale_id')->nullable()->constrained('grading_scales')->nullOnDelete();
            $table->string('title', 150);
            $table->string('exam_type', 50)->default('terminal'); // terminal, half_yearly, final, class_test, model_test
            $table->date('start_date');
            $table->date('end_date');
            $table->text('description')->nullable();
            $table->string('status', 30)->default('draft'); // draft, scheduled, ongoing, marks_entry, results_calculated, published, archived
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'academic_year_id', 'title'], 'exams_school_year_title_unique');
            $table->index(['school_id', 'status'], 'exams_school_status_idx');
        });

        Schema::create('exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->decimal('full_marks', 6, 2)->default(100.00);
            $table->decimal('pass_marks', 6, 2)->default(33.00);
            $table->date('exam_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('room_number', 50)->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();

            $table->unique(['exam_id', 'academic_class_id', 'subject_id'], 'exam_subjects_unique');
            $table->index(['school_id', 'exam_id', 'academic_class_id'], 'exam_subjects_school_exam_class_idx');
        });

        Schema::create('exam_subject_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_subject_id')->constrained('exam_subjects')->cascadeOnDelete();
            $table->string('name', 100); // Written, MCQ, Practical, CA
            $table->decimal('max_marks', 6, 2);
            $table->decimal('pass_marks', 6, 2)->default(0.00);
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();

            $table->index(['exam_subject_id', 'order_index'], 'exam_subj_comp_order_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_subject_components');
        Schema::dropIfExists('exam_subjects');
        Schema::dropIfExists('exams');
    }
};
