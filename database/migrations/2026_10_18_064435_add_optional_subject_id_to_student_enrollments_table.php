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
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->foreignId('optional_subject_id')
                ->nullable()
                ->after('academic_group_id')
                ->constrained('subjects')
                ->nullOnDelete();

            $table->index(['school_id', 'optional_subject_id'], 'enrollments_optional_subject_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropForeign(['optional_subject_id']);
            $table->dropIndex('enrollments_optional_subject_idx');
            $table->dropColumn('optional_subject_id');
        });
    }
};
