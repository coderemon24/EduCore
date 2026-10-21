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
        Schema::create('about_page_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();

            // Header & Hero Section
            $table->string('title')->nullable();
            $table->string('subtitle', 500)->nullable();
            $table->string('established_year', 50)->nullable();
            $table->string('banner_image')->nullable();

            // History & Narrative Section
            $table->string('history_heading')->nullable();
            $table->longText('history_content')->nullable();
            $table->string('history_image')->nullable();

            // Vision, Mission & Values Section
            $table->string('vision_title')->nullable();
            $table->text('vision_text')->nullable();
            $table->string('mission_title')->nullable();
            $table->text('mission_text')->nullable();
            $table->string('values_title')->nullable();
            $table->json('values_list')->nullable();

            // Dynamic Repeaters (JSON Arrays)
            $table->json('milestones')->nullable();
            $table->json('statistics')->nullable();
            $table->json('at_a_glance')->nullable();
            $table->json('committee_members')->nullable();
            $table->json('facilities')->nullable();

            // Dynamic Search Engine Optimization (SEO) & Social Graph
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();

            // Unique constraint: Exactly one about page record per school tenant
            $table->unique('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_page_contents');
    }
};
