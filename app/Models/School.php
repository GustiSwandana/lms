<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'npsn',
        'slug',
        'address',
        'logo_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (School $school) {
            if (empty($school->slug)) {
                $baseSlug = Str::slug($school->name);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $counter++;
                    $slug = "{$baseSlug}-{$counter}";
                }
                $school->slug = $slug;
            }
        });
    }

    /**
     * Get all users associated with this school.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all students associated with this school.
     *
     * @return HasMany<User, $this>
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }

    /**
     * Get all operators (school admins) associated with this school.
     *
     * @return HasMany<User, $this>
     */
    public function operators(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'school_admin');
    }

    /**
     * Get all courses authored by this school.
     *
     * @return HasMany<Course, $this>
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all support tickets filed from this school.
     *
     * @return HasMany<SupportTicket, $this>
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}
