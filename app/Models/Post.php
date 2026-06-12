<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'category_id',
        'body',
        'image',
        'is_published'
    ];

    public $with = ['author', 'category'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // public function scopePublished($query)
    // {
    //     return $query->where('is_published', true);
    // }

    // public function scopeUnpublished($query)
    // {
    //     return $query->where('is_published', false);
    // }

    // public function scopeAuthor($query, $authorId)
    // {
    //     return $query->where('user_id', $authorId);
    // }

    // public function scopeCategory($query, $categoryId)
    // {
    //     return $query->where('category_id', $categoryId);
    // }



    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }


    protected static function booted()
    {
        static::creating(function ($post) {
            // Hanya buat slug jika belum ada
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            // Hanya update slug jika title diubah
            if ($post->isDirty('title')) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}
