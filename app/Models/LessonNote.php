<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'timestamp_seconds',
        'content',
    ];

    /**
     * Get the user that owns the note.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson for this note.
     *
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Format timestamp seconds into MM:SS.
     */
    public function getFormattedTimestampAttribute(): string
    {
        $minutes = floor($this->timestamp_seconds / 60);
        $seconds = $this->timestamp_seconds % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
