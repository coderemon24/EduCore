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
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 100);
            $table->unsignedSmallInteger('numeric_code')->nullable();
            $table->unsignedSmallInteger('order_index')->default(0);
            $table->text('description')->nullable();
            $table->string('status', 24)->default('active');
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'name'], 'academic_classes_school_name_unique');
            $table->index(['school_id', 'status', 'order_index'], 'academic_classes_ordering_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_classes');
    }
};
