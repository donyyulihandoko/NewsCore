<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::factory(10)->is_author()->create();
        $category = Category::factory(10)->create();
        $post = Post::factory(20)->published()->recycle([$author, $category])->create();
        $user = User::factory(10)->create();
        Comment::factory(50)->recycle([$post, $user])->create();
    }
}
