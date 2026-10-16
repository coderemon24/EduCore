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
        Schema::create('class_subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('type', 24)->default('compulsory'); // compulsory, optional, elective
            $table->unsignedSmallInteger('full_marks')->default(100);
            $table->unsignedSmallInteger('pass_marks')->default(33);
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'academic_class_id', 'subject_id', 'academic_year_id'], 'class_subject_year_unique');
            $table->index(['school_id', 'academic_class_id', 'academic_year_id'], 'class_subject_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject_assignments');
    }
};
