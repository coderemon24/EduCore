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
        // 1. Timetable Slots Schedule
        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room_number', 50)->nullable();
            $table->enum('type', ['regular', 'lab', 'practical', 'break'])->default('regular');
            $table->timestamps();

            // Conflict Detection & Fast Schedule Lookup Indexes
            $table->index(['school_id', 'academic_year_id', 'section_id', 'day_of_week', 'start_time'], 'idx_tt_sec_day_time');
            $table->index(['school_id', 'academic_year_id', 'teacher_id', 'day_of_week', 'start_time'], 'idx_tt_teach_day_time');
            $table->index(['school_id', 'academic_year_id', 'room_number', 'day_of_week', 'start_time'], 'idx_tt_room_day_time');
        });

        // 2. Academic Holidays & Calendar
        Schema::create('academic_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->string('title', 191);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['holiday', 'weekend', 'exam_break', 'unplanned'])->default('holiday');
            $table->text('description')->nullable();
            $table->timestamps();

            // Index for date range overlap queries
            $table->index(['school_id', 'academic_year_id', 'start_date', 'end_date'], 'idx_holiday_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_holidays');
        Schema::dropIfExists('timetable_slots');
    }
};
