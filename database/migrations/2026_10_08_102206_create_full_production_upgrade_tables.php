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
        // 1. Add created_by & school_name to courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('category_id')->constrained('users')->nullOnDelete();
            $table->string('school_name')->nullable()->after('created_by');
        });

        // 2. Add last_watched_second to lesson_completions table
        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->integer('last_watched_second')->default(0)->after('lesson_id');
        });

        // 3. Add parent_id to lesson_comments table for replies
        Schema::table('lesson_comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('lesson_id')->constrained('lesson_comments')->cascadeOnDelete();
        });

        // 4. Create comment_likes table for persistent likes
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_comment_id')->constrained('lesson_comments')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_comment_id']);
        });

        // 5. Create quiz_attempts table for persistent quiz history and scores
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->boolean('passed')->default(false);
            $table->json('answers')->nullable();
            $table->timestamps();
        });

        // 6. Create lesson_resources table for PDF cheatsheets, GitHub links, etc.
        Schema::create('lesson_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('type')->default('link'); // pdf, github, zip, link
            $table->string('url');
            $table->timestamps();
        });

        // 7. Create certificates table for automated course graduation
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_code')->unique();
            $table->timestamp('issued_at');
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('lesson_resources');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('comment_likes');

        Schema::table('lesson_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->dropColumn('last_watched_second');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['created_by', 'school_name']);
        });
    }
};
