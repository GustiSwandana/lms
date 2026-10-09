<?php

namespace App\Models;

use App\Services\YouTubeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_title',
        'title',
        'slug',
        'youtube_url',
        'youtube_video_id',
        'thumbnail_url',
        'duration_seconds',
        'duration_formatted',
        'sort_order',
        'description',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (Lesson $lesson) {
            if ($lesson->isDirty('youtube_url') && ! empty($lesson->youtube_url)) {
                $videoId = YouTubeService::extractVideoId($lesson->youtube_url);
                if ($videoId) {
                    $lesson->youtube_video_id = $videoId;
                    if (empty($lesson->thumbnail_url)) {
                        $lesson->thumbnail_url = YouTubeService::getThumbnailUrl($videoId);
                    }
                }
            }

            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }

            if (empty($lesson->duration_formatted) && $lesson->duration_seconds > 0) {
                $lesson->duration_formatted = YouTubeService::formatDuration($lesson->duration_seconds);
            }
        });
    }

    /**
     * Get the course that owns the lesson.
     *
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get completions records for this lesson.
     *
     * @return HasMany<LessonCompletion, $this>
     */
    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /**
     * Get timestamped student notes for this lesson.
     *
     * @return HasMany<LessonNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(LessonNote::class)->orderBy('timestamp_seconds');
    }

    /**
     * Get comments for this lesson (top-level discussion threads).
     *
     * @return HasMany<LessonComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(LessonComment::class)
            ->whereNull('parent_id')
            ->with(['replies', 'likes'])
            ->latest();
    }

    /**
     * Get quiz questions for this lesson.
     *
     * @return HasMany<QuizQuestion, $this>
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get lesson resources (PDF, GitHub repo, ZIP).
     *
     * @return HasMany<LessonResource, $this>
     */
    public function resources(): HasMany
    {
        return $this->hasMany(LessonResource::class);
    }

    /**
     * Get quiz attempts for this lesson.
     *
     * @return HasMany<QuizAttempt, $this>
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get the latest quiz attempt for a given user.
     */
    public function quizAttemptFor(?User $user): ?QuizAttempt
    {
        if (! $user) {
            return null;
        }

        return $this->quizAttempts()->where('user_id', $user->id)->latest()->first();
    }

    /**
     * Get last watched second for a given user.
     */
    public function lastWatchedSecondFor(?User $user): int
    {
        if (! $user) {
            return 0;
        }

        return (int) ($this->completions()->where('user_id', $user->id)->value('last_watched_second') ?? 0);
    }

    /**
     * Check whether this lesson is completed by the given user.
     */
    public function isCompletedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->completions()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the YouTube Embed URL attribute.
     */
    public function getEmbedUrlAttribute(): string
    {
        return YouTubeService::getEmbedUrl($this->youtube_video_id);
    }

    /**
     * Get thumbnail URL with fallback to high quality.
     */
    public function getHqThumbnailAttribute(): string
    {
        if (! empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        return YouTubeService::getThumbnailUrl($this->youtube_video_id);
    }
}
