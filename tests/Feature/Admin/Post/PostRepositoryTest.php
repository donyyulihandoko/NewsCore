<?php

namespace Tests\Feature\Admin\Post;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private PostRepository $postRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepository = $this->app->make(PostRepository::class);
    }

    public function test_service_container_not_null()
    {
        $this->assertNotNull($this->postRepository);
    }

    public function test_get_posts_pagination()
    {
        Post::factory(20)->create();

        $result = $this->postRepository->getPostsPagination();
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_create_post()
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($user);

        $data =  [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ];

        $this->postRepository->createPost($data);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);
    }

    public function test_update_post()
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

        $data =  [
            'title' => 'Test Post update',
            'slug' => 'test-post update',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit update.'
        ];

        $this->postRepository->updatePost($post, $data);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post update',
            'slug' => 'test-post update',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit update.'
        ]);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);
    }

    public function test_remove_post()
    {
        $post = Post::factory()->create();

        $this->postRepository->removePost($post);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }
}
