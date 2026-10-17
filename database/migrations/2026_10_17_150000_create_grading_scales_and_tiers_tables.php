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
        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('fail_any_fails_all')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'name'], 'grading_scales_school_name_unique');
            $table->index(['school_id', 'is_active'], 'grading_scales_school_active_idx');
        });

        Schema::create('grade_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grading_scale_id')->constrained('grading_scales')->cascadeOnDelete();
            $table->string('grade', 10); // A+, A, A-, B, C, D, F
            $table->decimal('point', 4, 2)->default(0.00); // 5.00, 4.00, 3.50, 0.00
            $table->decimal('min_percentage', 5, 2); // e.g. 80.00
            $table->decimal('max_percentage', 5, 2); // e.g. 100.00
            $table->boolean('is_pass')->default(true);
            $table->string('remarks', 100)->nullable();
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();

            $table->index(['grading_scale_id', 'min_percentage', 'max_percentage'], 'grade_tiers_scale_range_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_tiers');
        Schema::dropIfExists('grading_scales');
    }
};
