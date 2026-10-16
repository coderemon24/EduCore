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
        Schema::create('academic_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('code', 32)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 24)->default('active');
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'name'], 'academic_groups_school_name_unique');
            $table->index(['school_id', 'status'], 'academic_groups_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_groups');
    }
};
