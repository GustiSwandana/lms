<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'type',
        'url',
    ];

    /**
     * Get the lesson that owns this resource.
     *
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get material symbol icon name based on type.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'pdf' => 'picture_as_pdf',
            'github' => 'code',
            'zip' => 'folder_zip',
            default => 'link',
        };
    }
}
