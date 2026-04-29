<?php

namespace Tests\Feature\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = $this->app->make(PostService::class);
        Storage::fake('public');
    }

    public function test_service_container_not_null(): void
    {
        $this->assertNotNull($this->postService);
    }

    public function test_create_post(): void
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($user);

        $result = $this->postService->createPost([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => UploadedFile::fake()->image('test-post.jpg')
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertTrue(Storage::disk('public')->exists($result->image));
    }

    public function test_update_post_without_changing_image(): void
    {
        // set up existing image
        $existingImage = 'post-images/image.jpg';
        $file = UploadedFile::fake()->image('image.jpg');
        $file->storeAs('post-images', 'image.jpg', 'public');

        // set up data old post
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => $existingImage
        ]);

        // update data post
        $this->postService->updatePost($post, [
            'title' => 'Test Post Update',
            'slug' => 'test-post-update',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

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

        $post->refresh();
        $this->assertTrue(Storage::disk('public')->exists($existingImage));
    }

    public function test_update_post_with_changing_image(): void
    {
        // set up existing image
        $oldImage = 'post-images/image.jpg';
        $file = UploadedFile::fake()->image('image.jpg');
        $file->storeAs('post-images', 'image.jpg', 'public');

        // set up data old post
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => $oldImage
        ]);

        // set up new image
        $newImage = UploadedFile::fake()->image('newImage.jpg');

        // update data post
        $this->postService->updatePost($post, [
            'title' => 'Test Post Update',
            'slug' => 'test-post-update',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => $newImage
        ]);

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

        $post->refresh();
        $this->assertFalse(Storage::disk('public')->exists($oldImage));
        $this->assertTrue(Storage::disk('public')->exists($post->refresh()->image));
    }

    public function test_remove_post(): void
    {
        $post = Post::factory()->create();
        $this->postService->removePost($post);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    public function test_find_by_slug()
    {
        $post = Post::factory()->create();
        $result = $this->postService->findBySlug($post);

        $this->assertEquals($post->id, $result->id);
    }

    // admin
    public function test_total_all_post()
    {
        Post::factory(10)->create();
        Post::factory(10)->published()->create();
        Post::factory(10)->pending()->create();

        $result = $this->postService->totalAllPosts();

        $this->assertNotNull($result);
        $this->assertEquals(30, $result);
    }

    public function test_total_published_post()
    {
        Post::factory(50)->published()->create();

        $result = $this->postService->totalPublishedPosts();

        $this->assertNotNull($result);
        $this->assertEquals(50, $result);
    }

    public function test_total_pending_post()
    {
        Post::factory(50)->pending()->create();

        $result = $this->postService->totalPendingPosts();

        $this->assertNotNull($result);
        $this->assertEquals(50, $result);
    }

    public function test_get_posts_pagination()
    {
        Post::factory(20)->create();

        $result = $this->postService->getPostsPagination(10);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_get_published_posts()
    {
        Post::factory(20)->published()->create();

        $result = $this->postService->getPublishedPosts(10);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_get_pending_posts()
    {
        Post::factory(20)->pending()->create();

        $result = $this->postService->getPendingPosts(10);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_aproval_pending_post()
    {
        $post = Post::factory()->pending()->create();
        $this->postService->approvalPendingPost($post, [
            'is_published' => true
        ]);

        $this->assertTrue($post->is_published);
    }

    public function test_recent_activity()
    {
        Post::factory(5)->create();
        $result = $this->postService->recentActivity();
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
        $result = $this->postService->getPostsByAuthorId($author->id, 10);

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

        $result = $this->postService->totalPostsByAuthor($author->id);

        $this->assertNotNull($result);
        $this->assertEquals(15, $result);
    }

    public function test_get_recent_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(5)->recycle($author)->create();
        Post::factory(5)->recycle($author)->pending()->create();
        Post::factory(5)->recycle($author)->published()->create();

        $result = $this->postService->getRecentPostsByAuthor($author->id);

        $this->assertNotNull($result);

        $result->each(function ($post) use ($author) {
            self::assertEquals($post->user_id, $author->id);
        });
    }

    public function test_total_pending_post_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->pending()->create();

        $result = $this->postService->totalPendingPostsByAuthor($author->id);

        $this->assertNotNull($result);
        $this->assertEquals(15, $result);
    }

    public function test_get_pending_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->pending()->create();

        $result = $this->postService->getPendingPostsByAuthor($author->id, 10);

        $this->assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }

    public function test_total_published_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->published()->create();

        $result = $this->postService->totalPublishedPostsByAuthor($author->id);

        $this->assertNotNull($result);
        $this->assertEquals(15, $result);
    }

    public function test_get_published_posts_by_author()
    {
        $author = User::factory()->is_author()->create();
        Post::factory(15)->recycle($author)->published()->create();

        $result = $this->postService->totalPublishedPostsByAuthor($author->id);

        $result = $this->postService->getPublishedPostsByAuthor($author->id, 10);

        $this->assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(15, $result->total());
    }

    // user
    public function test_get_user_recent_post()
    {
        Post::factory(10)->published()->create();
        $result = $this->postService->getUserRecentPosts();
        $this->assertNotNull($result);
    }

    public function test_get_user_published_posts()
    {
        Post::factory(30)->published()->create();
        $result = $this->postService->getUserPublishedPosts(10);
        self::assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(30, $result->total());
    }

    public function test_get_user_published_posts_by_category()
    {
        $category = Category::factory()->create();
        Post::factory(30)->published()->recycle($category)->create();
        $result = $this->postService->getUserPublishedPosts(10);
        self::assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(30, $result->total());

        $result->each(function ($post) use ($category) {
            $this->assertEquals($post->category_id, $category->id);
        });
    }
}
