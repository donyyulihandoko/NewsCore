<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'image' => 'https://picsum.photos/seed/' . Str::random(10) . '/600/600',
            'user_id' => User::factory()->is_author(),
            'category_id' => Category::factory(),
            'body' => fake()->paragraph(3),
            'is_published' => false
        ];
    }

    public function published()
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function pending()
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => false,
        ]);
    }
}
