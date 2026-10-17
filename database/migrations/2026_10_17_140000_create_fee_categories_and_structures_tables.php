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
        Schema::create('fee_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('code', 50);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Institutional uniqueness & status indexes
            $table->unique(['school_id', 'code'], 'fee_categories_school_code_unique');
            $table->index(['school_id', 'is_active'], 'fee_categories_school_active_idx');
        });

        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->nullable()->constrained('academic_classes')->cascadeOnDelete();
            $table->foreignId('fee_category_id')->constrained('fee_categories')->cascadeOnDelete();
            $table->string('name', 150);
            $table->decimal('amount', 12, 2);
            $table->string('frequency', 30)->default('monthly'); // one_time, monthly, quarterly, term, yearly
            $table->unsignedTinyInteger('due_day')->default(10); // Day of month or term
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Performance and lookup indexes
            $table->index(['school_id', 'academic_year_id', 'academic_class_id'], 'fee_struct_school_year_class_idx');
            $table->index(['school_id', 'fee_category_id'], 'fee_struct_school_category_idx');
            $table->index(['school_id', 'is_active'], 'fee_struct_school_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_categories');
    }
};
