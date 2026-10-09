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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('module_title')->nullable();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('youtube_url');
            $table->string('youtube_video_id', 20);
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->string('duration_formatted')->default('00:00');
            $table->integer('sort_order')->default(1);
            $table->longText('description')->nullable();
            $table->enum('status', ['published', 'draft', 'unlisted'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
