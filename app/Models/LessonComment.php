<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'parent_id',
        'user_name',
        'user_avatar',
        'timestamp_str',
        'content',
        'likes_count',
    ];

    /**
     * Get the user who commented.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson.
     *
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get parent comment if this is a reply.
     *
     * @return BelongsTo<LessonComment, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(LessonComment::class, 'parent_id');
    }

    /**
     * Get nested replies.
     *
     * @return HasMany<LessonComment, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(LessonComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get likes on this comment.
     *
     * @return HasMany<CommentLike, $this>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'lesson_comment_id');
    }

    /**
     * Check if a given user liked this comment.
     */
    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', $user->id);
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
