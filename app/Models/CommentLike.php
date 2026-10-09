<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_comment_id',
    ];

    /**
     * Get user who liked the comment.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get comment that was liked.
     *
     * @return BelongsTo<LessonComment, $this>
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(LessonComment::class, 'lesson_comment_id');
    }
}
