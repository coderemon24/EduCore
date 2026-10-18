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
        Schema::table('marksheet_settings', function (Blueprint $table) {
            $table->string('padding_top', 20)->default('10mm')->after('custom_css');
            $table->string('padding_right', 20)->default('12mm')->after('padding_top');
            $table->string('padding_bottom', 20)->default('10mm')->after('padding_right');
            $table->string('padding_left', 20)->default('12mm')->after('padding_bottom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marksheet_settings', function (Blueprint $table) {
            $table->dropColumn(['padding_top', 'padding_right', 'padding_bottom', 'padding_left']);
        });
    }
};
