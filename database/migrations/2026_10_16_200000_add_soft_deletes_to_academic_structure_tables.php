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
        Schema::table('academic_years', function (Blueprint $table) {
            $table->softDeletes()->after('notes');
            $table->index(['deleted_at', 'school_id', 'status'], 'academic_years_deleted_status_idx');
        });

        Schema::table('academic_classes', function (Blueprint $table) {
            $table->softDeletes()->after('status');
            $table->index(['deleted_at', 'school_id', 'status'], 'academic_classes_deleted_status_idx');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->softDeletes()->after('status');
            $table->index(['deleted_at', 'school_id', 'academic_class_id'], 'sections_deleted_class_idx');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->softDeletes()->after('status');
            $table->index(['deleted_at', 'school_id', 'status'], 'subjects_deleted_status_idx');
        });

        Schema::table('class_subject_assignments', function (Blueprint $table) {
            $table->softDeletes()->after('pass_marks');
            $table->index(['deleted_at', 'school_id', 'academic_class_id'], 'class_subject_assignments_deleted_idx');
        });

        Schema::table('academic_groups', function (Blueprint $table) {
            $table->softDeletes()->after('status');
            $table->index(['deleted_at', 'school_id', 'status'], 'academic_groups_deleted_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_groups', function (Blueprint $table) {
            $table->dropIndex('academic_groups_deleted_status_idx');
            $table->dropSoftDeletes();
        });

        Schema::table('class_subject_assignments', function (Blueprint $table) {
            $table->dropIndex('class_subject_assignments_deleted_idx');
            $table->dropSoftDeletes();
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex('subjects_deleted_status_idx');
            $table->dropSoftDeletes();
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropIndex('sections_deleted_class_idx');
            $table->dropSoftDeletes();
        });

        Schema::table('academic_classes', function (Blueprint $table) {
            $table->dropIndex('academic_classes_deleted_status_idx');
            $table->dropSoftDeletes();
        });

        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropIndex('academic_years_deleted_status_idx');
            $table->dropSoftDeletes();
        });
    }
};
