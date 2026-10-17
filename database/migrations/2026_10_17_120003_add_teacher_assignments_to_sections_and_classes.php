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
        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('class_teacher_id')->nullable()->after('academic_class_id')->constrained('staff')->nullOnDelete();
            $table->index(['school_id', 'class_teacher_id'], 'sections_school_teacher_idx');
        });

        Schema::table('class_subject_assignments', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->after('academic_year_id')->constrained('staff')->nullOnDelete();
            $table->index(['school_id', 'teacher_id'], 'assignments_school_teacher_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_subject_assignments', function (Blueprint $table) {
            $table->dropIndex('assignments_school_teacher_idx');
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropIndex('sections_school_teacher_idx');
            $table->dropForeign(['class_teacher_id']);
            $table->dropColumn('class_teacher_id');
        });
    }
};
