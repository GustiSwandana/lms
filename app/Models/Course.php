<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'school_id',
        'created_by',
        'school_name',
        'title',
        'slug',
        'description',
        'instructor_name',
        'instructor_title',
        'instructor_avatar',
        'thumbnail_url',
        'level',
        'status',
        'is_visible_to_guest',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible_to_guest' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        static::saving(function (Course $course) {
            if ($course->school_id && empty($course->school_name)) {
                $course->school_name = School::find($course->school_id)?->name ?? $course->school_name;
            } elseif (! empty($course->school_name) && empty($course->school_id)) {
                $school = School::firstOrCreate(
                    ['name' => $course->school_name],
                    ['is_active' => true]
                );
                $course->school_id = $school->id;
            } elseif ($course->school_id && ! empty($course->school_name)) {
                $school = School::find($course->school_id);
                if ($school && $course->school_name !== $school->name) {
                    $course->school_name = $school->name;
                }
            }

            try {
                Cache::forget('active_categories');
                Cache::forget('global_catalog_stats');
            } catch (\Throwable $e) {
                // Graceful degradation when cache driver/redis is unreachable
            }
        });

        static::deleted(function () {
            try {
                Cache::forget('active_categories');
                Cache::forget('global_catalog_stats');
            } catch (\Throwable $e) {
                // Graceful degradation when cache driver/redis is unreachable
            }
        });
    }

    /**
     * Get the school that owns the course.
     *
     * @return BelongsTo<School, $this>
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user that created the course.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get certificates issued for this course.
     *
     * @return HasMany<Certificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the category that owns the course.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all lessons in this course ordered by sort_order.
     *
     * @return HasMany<Lesson, $this>
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    /**
     * Get bookmarks for this course.
     *
     * @return HasMany<CourseBookmark, $this>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(CourseBookmark::class);
    }

    /**
     * Check if course is bookmarked by user.
     */
    public function isBookmarkedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the total duration of the course in seconds.
     */
    public function getTotalDurationSecondsAttribute(): int
    {
        return (int) $this->lessons->sum('duration_seconds');
    }

    /**
     * Get the count of lessons completed by a given user.
     */
    public function completedLessonsCountFor(?User $user): int
    {
        if (! $user) {
            return 0;
        }

        $lessonIds = $this->lessons->pluck('id');

        return $user->lessonCompletions()->whereIn('lesson_id', $lessonIds)->count();
    }

    /**
     * Check if a user can manage (edit/delete) this course based on RBAC & multi-tenancy.
     */
    public function canManage(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isSchoolAdmin() || $user->isGuru()) {
            // Can manage if matches school name or created by this user
            return (! empty($this->school_name) && $this->school_name === $user->school_name)
                || ($this->created_by === $user->id);
        }

        return false;
    }

    /**
     * Get the completion percentage of the course for a given user.
     */
    public function progressPercentageFor(?User $user): int
    {
        if (! $user) {
            return 0;
        }

        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completed = $this->completedLessonsCountFor($user);

        return (int) round(($completed / $totalLessons) * 100);
    }

    /**
     * Get issued certificate for a given user.
     */
    public function getCertificateFor(?User $user): ?Certificate
    {
        if (! $user) {
            return null;
        }

        return $this->certificates()->where('user_id', $user->id)->first();
    }

    /**
     * Get quizzes associated with this course.
     *
     * @return HasMany<Quiz, $this>
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Check if a given user is eligible for a course completion certificate.
     * Requirements:
     * 1. 100% of lessons/videos in this course must be completed.
     * 2. All published course-level quizzes must be passed (score >= passing_grade).
     * 3. All lessons that have checkpoint quiz questions must be attempted and passed.
     */
    public function isEligibleForCertificate(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) {
            return false;
        }

        // 1. Must complete 100% of lessons / videos
        if ($this->completedLessonsCountFor($user) < $totalLessons) {
            return false;
        }

        // 2. Course-level published quizzes must all be passed
        $quizzes = $this->quizzes()->where('is_published', true)->get();
        if ($quizzes->isNotEmpty()) {
            foreach ($quizzes as $quiz) {
                if (! $quiz->hasPassed($user)) {
                    return false;
                }
            }
        }

        // 3. Lesson-level checkpoint quizzes must all be attempted and passed
        $lessonIdsWithQuestions = QuizQuestion::whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->pluck('lesson_id')
            ->unique();

        if ($lessonIdsWithQuestions->isNotEmpty()) {
            foreach ($lessonIdsWithQuestions as $lessonId) {
                $hasPassed = QuizAttempt::where('user_id', $user->id)
                    ->where('lesson_id', $lessonId)
                    ->where('passed', true)
                    ->exists();

                if (! $hasPassed) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if user has passed all quizzes (course-level and lesson-level) in this course.
     */
    public function hasPassedAllQuizzes(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        $quizzes = $this->quizzes()->where('is_published', true)->get();
        if ($quizzes->isNotEmpty()) {
            foreach ($quizzes as $quiz) {
                if (! $quiz->hasPassed($user)) {
                    return false;
                }
            }
        }

        $lessonIdsWithQuestions = QuizQuestion::whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->pluck('lesson_id')
            ->unique();

        if ($lessonIdsWithQuestions->isNotEmpty()) {
            foreach ($lessonIdsWithQuestions as $lessonId) {
                $hasPassed = QuizAttempt::where('user_id', $user->id)
                    ->where('lesson_id', $lessonId)
                    ->where('passed', true)
                    ->exists();

                if (! $hasPassed) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Total number of quiz evaluations in this course.
     */
    public function totalQuizzesCount(): int
    {
        $courseQuizzesCount = $this->quizzes()->where('is_published', true)->count();
        $lessonsWithQuestionsCount = QuizQuestion::whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->whereNull('quiz_id')
            ->pluck('lesson_id')
            ->unique()
            ->count();

        return $courseQuizzesCount + $lessonsWithQuestionsCount;
    }

    /**
     * Number of quiz evaluations passed by a given user.
     */
    public function passedQuizzesCountFor(?User $user): int
    {
        if (! $user) {
            return 0;
        }

        $passedCount = 0;
        $courseQuizzes = $this->quizzes()->where('is_published', true)->get();
        foreach ($courseQuizzes as $quiz) {
            if ($quiz->hasPassed($user)) {
                $passedCount++;
            }
        }

        $lessonsWithQuestions = QuizQuestion::whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->whereNull('quiz_id')
            ->pluck('lesson_id')
            ->unique();

        foreach ($lessonsWithQuestions as $lessonId) {
            if (QuizAttempt::where('user_id', $user->id)->where('lesson_id', $lessonId)->where('passed', true)->exists()) {
                $passedCount++;
            }
        }

        return $passedCount;
    }
}
