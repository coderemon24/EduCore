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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->string('type', 50)->default('general'); // general, academic, exam, holiday, event, urgent
            $table->string('target_audience', 50)->default('all'); // all, teachers, students, guardians, class_specific
            $table->foreignId('academic_class_id')->nullable()->constrained('academic_classes')->nullOnDelete();
            $table->boolean('is_pinned')->default(false);
            $table->string('status', 30)->default('published'); // draft, published, archived
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for fast tenant-scoped queries
            $table->index(['school_id', 'status', 'published_at']);
            $table->index(['school_id', 'target_audience']);
            $table->index(['school_id', 'is_pinned']);
            $table->index(['school_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
