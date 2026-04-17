<?php

namespace Tests\Feature\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = $this->app->make(PostService::class);
    }

    public function test_service_container_not_null(): void
    {
        $this->assertNotNull($this->postService);
    }

    public function test_get_posts_pagination(): void
    {
        Post::factory(20)->create();
        $result =  $this->postService->getPostsPagination(10);

        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_create_post(): void
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($user);

        $data = [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ];

        $this->postService->createPost($data);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);
    }

    public function test_update_post(): void
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->actingAs($user);

        $data = [
            'title' => 'Test Post Update',
            'slug' => 'test-post-update',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ];

        $this->postService->updatePost($post, $data);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post Update',
            'slug' => 'test-post-update',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);
    }

    public function test_remove_post(): void
    {
        $post = Post::factory()->create();
        $this->postService->removePost($post);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }
}
