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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('phone', 30);
            $table->string('email', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('national_id', 50)->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();

            // Institutional lookup performance indexes
            $table->index(['school_id', 'phone'], 'guardians_school_phone_idx');
            $table->index(['school_id', 'name'], 'guardians_school_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
