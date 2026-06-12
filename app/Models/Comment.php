<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = [
        'user_id', 
        'post_id', 
        'body'
    ];

    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
