<?php

namespace Tests\Feature\Repositories;

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

    public function test_create_post()
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($user);

        $data =  [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => 'post-images/test-post.jpg'
        ];

        $this->postRepository->createPost($data);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => 'post-images/test-post.jpg'
        ]);
    }

    public function test_update_post()
    {
        $post = Post::factory()->create(['title' => 'old post']);
        $this->postRepository->updatePost($post, [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => 'post-images/test-post.jpg'
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => 'post-images/test-post.jpg'
        ]);

        $this->assertDatabaseMissing('posts', [
            'title' => 'old post'
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

    public function test_find_by_slug()
    {
        $post = Post::factory()->create();
        $result = $this->postRepository->findBySlug($post);

        $this->assertEquals($post->id, $result->id);
    }

    // admin
    public function test_total_all_post()
    {
        Post::factory(10)->create();
        Post::factory(10)->published()->create();
        Post::factory(10)->pending()->create();

        $result = $this->postRepository->totalAllPosts();

        $this->assertNotNull($result);
        $this->assertEquals(30, $result);
    }

    public function test_total_published_post()
    {
        Post::factory(50)->published()->create();

        $result = $this->postRepository->totalPublishedPosts();

        $this->assertNotNull($result);
        $this->assertEquals(50, $result);
    }

    public function test_total_pending_post()
    {
        Post::factory(50)->pending()->create();

        $result = $this->postRepository->totalPendingPosts();

        $this->assertNotNull($result);
        $this->assertEquals(50, $result);
    }

    public function test_get_posts_pagination()
    {
        Post::factory(20)->create();

        $result = $this->postRepository->getPostsPagination(10);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_get_published_posts()
    {
        Post::factory(20)->published()->create();

        $result = $this->postRepository->getPublishedPosts(10);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_get_pending_posts()
    {
        Post::factory(20)->pending()->create();

        $result = $this->postRepository->getPendingPosts(10);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_aproval_pending_post()
    {
        $post = Post::factory()->pending()->create();
        $this->postRepository->approvalPendingPost($post, [
            'is_published' => true
        ]);

        $this->assertTrue($post->is_published);
    }

    public function test_recent_activity()
    {
        Post::factory(5)->create();
        $result = $this->postRepository->recentActivity();
        $this->assertNotNull($result);

        $result->each(function ($post) {
            self::assertEquals($post->is_published, true);
        });
    }

    // author   
    public function test_get_posts_by_author_id()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(5)->recycle($author)->create();
        Post::factory(5)->recycle($author)->pending()->create();
        Post::factory(5)->recycle($author)->published()->create();

        // $this->actingAs($author);
        $result = $this->postRepository->getPostsByAuthorId($author->id, 10);

        $this->assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }

    public function test_total_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(5)->recycle($author)->create();
        Post::factory(5)->recycle($author)->pending()->create();
        Post::factory(5)->recycle($author)->published()->create();

        $result = $this->postRepository->totalPostsByAuthor($author->id);

        $this->assertNotNull($result);
        $this->assertEquals(15, $result);
    }

    public function test_get_recent_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(5)->recycle($author)->create();
        Post::factory(5)->recycle($author)->pending()->create();
        Post::factory(5)->recycle($author)->published()->create();

        $result = $this->postRepository->getRecentPostsByAuthor($author->id);

        $this->assertNotNull($result);

        $result->each(function ($post) use ($author) {
            self::assertEquals($post->user_id, $author->id);
        });
    }

    public function test_total_pending_post_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->pending()->create();

        $result = $this->postRepository->totalPendingPostsByAuthor($author->id);

        $this->assertNotNull($result);
        $this->assertEquals(15, $result);
    }

    public function test_get_pending_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->pending()->create();

        $result = $this->postRepository->getPendingPostsByAuthor($author->id, 10);

        $this->assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }

    public function test_total_published_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->published()->create();

        $result = $this->postRepository->totalPublishedPostsByAuthor($author->id);

        $this->assertNotNull($result);
        $this->assertEquals(15, $result);
    }

    public function test_get_published_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->published()->create();

        $result = $this->postRepository->totalPublishedPostsByAuthor($author->id);

        $result = $this->postRepository->getPublishedPostsByAuthor($author->id, 10);

        $this->assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }

    // user
    public function test_get_user_recent_post()
    {
        Post::factory(10)->published()->create();
        $result = $this->postRepository->getUserRecentPosts();
        $this->assertNotNull($result);
    }

    public function test_get_user_published_posts()
    {
        Post::factory(30)->published()->create();
        $result = $this->postRepository->getUserPublishedPosts(10);
        self::assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(30, $result->total());
    }

    public function test_get_user_published_posts_by_category()
    {
        $category = Category::factory()->create();
        Post::factory(30)->published()->recycle($category)->create();
        $result = $this->postRepository->getUserPublishedPosts(10);
        self::assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(30, $result->total());

        $result->each(function ($post) use ($category) {
            $this->assertEquals($post->category_id, $category->id);
        });
    }
}
