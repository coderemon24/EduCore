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
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('exam_subject_id')->constrained('exam_subjects')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->string('attendance_status', 20)->default('present'); // present, absent, exempt, incomplete
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->boolean('is_passed')->default(false);
            $table->foreignId('grade_tier_id')->nullable()->constrained('grade_tiers')->nullOnDelete();
            $table->string('remarks', 255)->nullable();
            $table->foreignId('entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['exam_subject_id', 'student_id'], 'exam_marks_subject_student_unique');
            $table->index(['school_id', 'exam_id', 'academic_class_id', 'section_id'], 'exam_marks_lookup_idx');
        });

        Schema::create('exam_mark_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_mark_id')->constrained('exam_marks')->cascadeOnDelete();
            $table->foreignId('exam_subject_component_id')->constrained('exam_subject_components')->cascadeOnDelete();
            $table->decimal('marks_obtained', 6, 2)->nullable();
            $table->boolean('is_passed')->default(true);
            $table->timestamps();

            $table->unique(['exam_mark_id', 'exam_subject_component_id'], 'mark_component_unique');
        });

        Schema::create('exam_mark_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_mark_id')->constrained('exam_marks')->cascadeOnDelete();
            $table->decimal('previous_marks', 6, 2)->nullable();
            $table->decimal('new_marks', 6, 2)->nullable();
            $table->string('previous_status', 20)->nullable();
            $table->string('new_status', 20)->nullable();
            $table->text('reason');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['exam_mark_id', 'created_at'], 'exam_mark_histories_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_mark_histories');
        Schema::dropIfExists('exam_mark_components');
        Schema::dropIfExists('exam_marks');
    }
};
