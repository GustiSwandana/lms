<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'school_name',
        'is_active',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            if ($user->school_id && empty($user->school_name)) {
                $user->school_name = School::find($user->school_id)?->name ?? $user->school_name;
            } elseif (! empty($user->school_name) && empty($user->school_id)) {
                $school = School::firstOrCreate(
                    ['name' => $user->school_name],
                    ['is_active' => true]
                );
                $user->school_id = $school->id;
            } elseif ($user->school_id && ! empty($user->school_name)) {
                $school = School::find($user->school_id);
                if ($school && $user->school_name !== $school->name) {
                    $user->school_name = $school->name;
                }
            }
        });
    }

    /**
     * Get the school that the user belongs to.
     *
     * @return BelongsTo<School, $this>
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Support tickets submitted by this user.
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Check if user is Super Admin (Admin Pusat).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is School Admin (Admin Sekolah).
     */
    public function isSchoolAdmin(): bool
    {
        return $this->role === 'school_admin';
    }

    /**
     * Check if user is a student (Siswa).
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is an admin (Super Admin or School Admin).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'school_admin', 'admin'], true);
    }

    /**
     * Check if user has any of the specified roles.
     *
     * @param  string|list<string>  $roles
     */
    public function hasRole(string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : explode(',', $roles);

        return in_array($this->role, $roleList, true);
    }

    /**
     * Get human-readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Admin Pusat',
            'school_admin' => 'Admin Sekolah',
            'student' => 'Siswa',
            default => 'Pengguna',
        };
    }

    /**
     * Get all lesson completions for the user.
     *
     * @return HasMany<LessonCompletion, $this>
     */
    public function lessonCompletions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /**
     * Get all lessons completed by the user.
     *
     * @return BelongsToMany<Lesson, $this>
     */
    public function completedLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_completions')
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    /**
     * Get notes written by user.
     *
     * @return HasMany<LessonNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(LessonNote::class);
    }

    /**
     * Get bookmarks created by user.
     *
     * @return HasMany<CourseBookmark, $this>
     */
    public function bookmarks(): HasMany
    {
        return $this->hasMany(CourseBookmark::class);
    }

    /**
     * Get courses bookmarked by user.
     *
     * @return BelongsToMany<Course, $this>
     */
    public function bookmarkedCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_bookmarks')
            ->withTimestamps();
    }

    /**
     * Check if user has completed a specific lesson.
     */
    public function hasCompletedLesson(int $lessonId): bool
    {
        return $this->lessonCompletions()->where('lesson_id', $lessonId)->exists();
    }

    /**
     * Get certificates earned by user.
     *
     * @return HasMany<Certificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get quiz attempts by user.
     *
     * @return HasMany<QuizAttempt, $this>
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get courses created by user.
     *
     * @return HasMany<Course, $this>
     */
    public function createdCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    /**
     * Get list of unique registered school names across the application.
     *
     * @return Collection<int, string>
     */
    public static function getRegisteredSchools(): Collection
    {
        $fromSchools = School::where('is_active', true)->orderBy('name')->pluck('name');
        if ($fromSchools->isNotEmpty()) {
            return $fromSchools;
        }

        return self::whereNotNull('school_name')
            ->where('school_name', '!=', '')
            ->whereIn('role', ['school_admin', 'student'])
            ->distinct()
            ->orderBy('school_name')
            ->pluck('school_name');
    }
}
