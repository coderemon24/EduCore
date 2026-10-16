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
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 100);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->string('status', 24)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'name'], 'academic_years_school_name_unique');
            $table->index(['school_id', 'is_current', 'status'], 'academic_years_active_session_idx');
            $table->index(['school_id', 'start_date', 'end_date'], 'academic_years_dates_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
