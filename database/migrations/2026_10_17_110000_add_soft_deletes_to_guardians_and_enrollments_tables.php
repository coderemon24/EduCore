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
        Schema::table('guardians', function (Blueprint $table) {
            $table->softDeletes()->after('photo');
            $table->index(['deleted_at', 'school_id'], 'guardians_deleted_school_idx');
        });

        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->softDeletes()->after('remarks');
            $table->index(['deleted_at', 'school_id', 'student_id'], 'student_enrollments_deleted_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropIndex('student_enrollments_deleted_idx');
            $table->dropSoftDeletes();
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->dropIndex('guardians_deleted_school_idx');
            $table->dropSoftDeletes();
        });
    }
};
