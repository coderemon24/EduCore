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
        Schema::table('schools', function (Blueprint $table) {
            $table->index(['deleted_at', 'status', 'created_at'], 'schools_lifecycle_listing_idx');
            $table->index(['deleted_at', 'created_at'], 'schools_listing_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropIndex('schools_lifecycle_listing_idx');
            $table->dropIndex('schools_listing_idx');
        });
    }
};
