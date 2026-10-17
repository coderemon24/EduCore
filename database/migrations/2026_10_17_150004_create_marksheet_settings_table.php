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
        Schema::create('marksheet_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('background_image_path', 255)->nullable();
            $table->string('school_logo_path', 255)->nullable();
            $table->string('principal_signature_path', 255)->nullable();
            $table->string('headmaster_title', 100)->default('Head Master');
            $table->boolean('show_highest_marks')->default(true);
            $table->boolean('show_attendance_report')->default(true);
            $table->boolean('show_extra_activities')->default(true);
            $table->boolean('show_gpa_grading_scale')->default(true);
            $table->string('header_title', 255)->nullable();
            $table->string('sub_header_title', 255)->nullable();
            $table->text('custom_css')->nullable();
            $table->timestamps();

            $table->unique('school_id', 'marksheet_settings_school_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marksheet_settings');
    }
};
