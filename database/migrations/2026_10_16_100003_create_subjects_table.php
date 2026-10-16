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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('code', 32);
            $table->string('type', 24)->default('theory'); // theory, practical, both
            $table->decimal('credit_hours', 4, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 24)->default('active');
            $table->timestamps();

            // Institutional uniqueness & composite performance indexes
            $table->unique(['school_id', 'code'], 'subjects_school_code_unique');
            $table->unique(['school_id', 'name'], 'subjects_school_name_unique');
            $table->index(['school_id', 'status', 'type'], 'subjects_listing_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
