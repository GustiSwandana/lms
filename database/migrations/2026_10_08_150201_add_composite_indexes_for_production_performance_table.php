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
            $table->index(['school_id', 'role', 'is_active'], 'users_school_role_active_idx');
            $table->index(['school_name', 'role', 'is_active'], 'users_school_name_role_active_idx');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->index(['school_id', 'status'], 'courses_school_status_idx');
            $table->index(['category_id', 'status'], 'courses_category_status_idx');
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->index(['user_id', 'lesson_id'], 'completions_user_lesson_idx');
            $table->index(['user_id', 'completed_at'], 'completions_user_completed_idx');
        });

        Schema::table('lesson_comments', function (Blueprint $table) {
            $table->index(['lesson_id', 'parent_id', 'created_at'], 'comments_lesson_parent_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_comments', function (Blueprint $table) {
            $table->dropIndex('comments_lesson_parent_created_idx');
        });

        Schema::table('lesson_completions', function (Blueprint $table) {
            $table->dropIndex('completions_user_lesson_idx');
            $table->dropIndex('completions_user_completed_idx');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_school_status_idx');
            $table->dropIndex('courses_category_status_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_school_role_active_idx');
            $table->dropIndex('users_school_name_role_active_idx');
        });
    }
};
