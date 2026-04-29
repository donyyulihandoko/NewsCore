<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::factory(10)->is_author()->create();
        $category = Category::factory(10)->create();

        Post::factory(50)->recycle([$author, $category])->create();
        Post::factory(20)->published()->recycle([$author, $category])->create();
    }
}
