<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'description',
        'passing_grade',
        'time_limit_minutes',
        'shuffle_questions',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'passing_grade' => 'integer',
            'time_limit_minutes' => 'integer',
            'shuffle_questions' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Get the course for this quiz.
     *
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson for this quiz if associated with a single lesson.
     *
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get questions for this quiz.
     *
     * @return HasMany<QuizQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get user attempts for this quiz.
     *
     * @return HasMany<QuizAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get the latest attempt for a given user.
     */
    public function latestAttemptFor(?User $user): ?QuizAttempt
    {
        if (! $user) {
            return null;
        }

        return $this->attempts()->where('user_id', $user->id)->latest()->first();
    }

    /**
     * Check if a given user has passed this quiz with at least passing_grade.
     */
    public function hasPassed(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->attempts()
            ->where('user_id', $user->id)
            ->where('passed', true)
            ->where('score', '>=', $this->passing_grade)
            ->exists();
    }
}
