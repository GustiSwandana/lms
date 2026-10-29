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
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('address');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'is_visible_to_guest')) {
                $table->boolean('is_visible_to_guest')->default(false)->after('status')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'is_visible_to_guest')) {
                $table->dropIndex(['is_visible_to_guest']);
                $table->dropColumn('is_visible_to_guest');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('users', 'phone')) {
                $cols[] = 'phone';
            }
            if (Schema::hasColumn('users', 'address')) {
                $cols[] = 'address';
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
