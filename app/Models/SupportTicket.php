<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'user_id',
        'school_id',
        'school_name',
        'subject',
        'category',
        'priority',
        'status',
        'description',
        'admin_response',
        'responded_by',
        'responded_at',
        'system_logs',
    ];

    protected $casts = [
        'system_logs' => 'array',
        'responded_at' => 'datetime',
    ];

    /**
     * Booted model hooks.
     */
    protected static function booted(): void
    {
        static::creating(function (SupportTicket $ticket) {
            if (empty($ticket->ticket_code)) {
                $prefix = 'TIK';
                if ($ticket->school_id) {
                    $school = School::find($ticket->school_id);
                    if ($school && $school->slug) {
                        $prefix = 'TIK-'.strtoupper(substr(str_replace('-', '', $school->slug), 0, 5));
                    }
                }
                $ticket->ticket_code = $prefix.'-'.strtoupper(bin2hex(random_bytes(3)));
            }

            if (empty($ticket->school_name) && $ticket->school_id) {
                $ticket->school_name = School::where('id', $ticket->school_id)->value('name');
            }
        });
    }

    /**
     * The author / sender of the support ticket (usually school_admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Associated school.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * The Super Admin responder.
     */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Priority label and badge styling helper.
     */
    public function getPriorityBadgeAttribute(): array
    {
        return match ($this->priority) {
            'kritis' => ['label' => 'Kritis', 'bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300'],
            'tinggi' => ['label' => 'Tinggi', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300'],
            'rendah' => ['label' => 'Rendah', 'bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'border' => 'border-slate-300'],
            default => ['label' => 'Sedang', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300'],
        };
    }

    /**
     * Status label and badge styling helper.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'selesai' => ['label' => 'Terselesaikan', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300', 'icon' => 'check_circle'],
            'diproses' => ['label' => 'Sedang Diproses Pusat', 'bg' => 'bg-cyan-100', 'text' => 'text-cyan-800', 'border' => 'border-cyan-300', 'icon' => 'hourglass_top'],
            'ditutup' => ['label' => 'Ditutup', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => 'cancel'],
            default => ['label' => 'Menunggu Respon Pusat', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'icon' => 'schedule'],
        };
    }
}
