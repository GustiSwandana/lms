<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::saved(function () {
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
     * Get the courses under this category.
     *
     * @return HasMany<Course, $this>
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
