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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->cascadeOnDelete();
            $table->string('status', 24)->default('active')->after('password')->index();
            $table->string('phone', 32)->nullable()->after('status');
            $table->string('avatar')->nullable()->after('phone');

            $table->index(['school_id', 'status'], 'users_school_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_school_status_idx');
            $table->dropForeign(['school_id']);
            $table->dropColumn(['school_id', 'status', 'phone', 'avatar']);
        });
    }
};
