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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_class_id')->constrained('academic_classes')->cascadeOnDelete();
            $table->string('name', 50);
            $table->unsignedSmallInteger('capacity')->nullable()->default(40);
            $table->string('room_number', 50)->nullable();
            $table->string('status', 24)->default('active');
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'academic_class_id', 'name'], 'sections_class_name_unique');
            $table->index(['school_id', 'academic_class_id', 'status'], 'sections_lookup_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
