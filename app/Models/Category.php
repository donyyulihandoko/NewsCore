<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $table = 'categories';
    protected $guarded = ['id'];
    protected $fillable = ['name', 'slug', 'description', 'image'];
    // protected $with = ['posts'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }

    // auto slug
    protected static function booted()
    {
        static::creating(function ($category) {
            // Hanya buat slug jika belum ada
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->title);
            }
        });

        static::updating(function ($category) {
            // Hanya update slug jika title diubah
            if ($category->isDirty('title')) {
                $category->slug = Str::slug($category->title);
            }
        });
    }

    // public function totalPostPerCategory(int $categoryId): ?int
    // {
    //     return $this->posts()
    //         ->where('category_id', $categoryId)
    //         ->count();
    // }

    // public function publishedPost(): ?int
    // {
    //     return $this->posts()
    //         ->where('is_published', true)
    //         ->count();
    // }
}
