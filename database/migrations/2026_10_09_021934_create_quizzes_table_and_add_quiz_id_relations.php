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
        // 1. Quizzes table
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('passing_grade')->default(75);
            $table->integer('time_limit_minutes')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // 2. Add quiz_id to quiz_questions
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->foreignId('quiz_id')->nullable()->after('lesson_id')->constrained('quizzes')->cascadeOnDelete();
        });

        // 3. Add quiz_id to quiz_attempts
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->foreignId('quiz_id')->nullable()->after('lesson_id')->constrained('quizzes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropColumn('quiz_id');
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropColumn('quiz_id');
        });

        Schema::dropIfExists('quizzes');
    }
};
