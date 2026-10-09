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
        // 1. Course Bookmarks
        Schema::create('course_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });

        // 2. Lesson Notes (Timestamped notes by students)
        Schema::create('lesson_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->integer('timestamp_seconds')->default(0);
            $table->text('content');
            $table->timestamps();
        });

        // 3. Lesson Community Comments & Q&A
        Schema::create('lesson_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('user_name');
            $table->string('user_avatar')->nullable();
            $table->string('timestamp_str')->nullable();
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->timestamps();
        });

        // 4. Interactive Quiz Checkpoints
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->json('options');
            $table->integer('correct_answer_index')->default(0);
            $table->text('explanation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('lesson_comments');
        Schema::dropIfExists('lesson_notes');
        Schema::dropIfExists('course_bookmarks');
    }
};
