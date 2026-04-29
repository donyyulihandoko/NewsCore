<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create();
        User::factory()->author()->create();
        User::factory()->create([
            'email' => 'donyyulihandoko@gmail.com'
        ]);
        // Post::factory(50)->recycle([User::factory(10)->create(), Category::factory(20)->create()])->create();
        // Post::factory(20)->published()->recycle([User::factory(10)->create(), Category::factory(20)->create()])->create();
    }
}
