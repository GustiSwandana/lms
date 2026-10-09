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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 32)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->string('school_name')->nullable();
            $table->string('subject');
            $table->string('category')->default('teknis'); // teknis, sinkronisasi_youtube, akun_siswa, kurikulum, lainnya
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang');
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditutup'])->default('menunggu');
            $table->text('description');
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->json('system_logs')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status'], 'tickets_school_status_idx');
            $table->index(['status', 'priority'], 'tickets_status_priority_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
