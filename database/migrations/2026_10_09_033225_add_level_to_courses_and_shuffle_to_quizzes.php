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
        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'level')) {
                $table->string('level')->default('Semua Tingkat')->nullable()->after('description');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (! Schema::hasColumn('quizzes', 'shuffle_questions')) {
                $table->boolean('shuffle_questions')->default(false)->after('time_limit_minutes');
            }
            if (! Schema::hasColumn('quizzes', 'time_limit_minutes')) {
                $table->integer('time_limit_minutes')->nullable()->after('passing_grade');
            }
            if (! Schema::hasColumn('quizzes', 'passing_grade')) {
                $table->integer('passing_grade')->default(75)->after('title');
            }
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'shuffle_questions')) {
                $table->dropColumn('shuffle_questions');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'level')) {
                $table->dropColumn('level');
            }
        });
    }
};
