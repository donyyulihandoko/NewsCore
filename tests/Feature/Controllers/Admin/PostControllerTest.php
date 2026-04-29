<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        Storage::fake('public');
    }

    public function test_index_success(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.posts.index'));
        $response->assertStatus(200);
    }

    public function test_index_failed_not_login(): void
    {
        $response = $this->get(route('admin.posts.index'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_index_failed_wrong_role_users(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.posts.index'));
        $response->assertStatus(404);
    }

    public function test_create_success(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.posts.create'));
        $response->assertStatus(200);
    }

    public function test_create_failed_not_login(): void
    {
        $response = $this->get(route('admin.posts.create'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_create_failed_wrong_role_users(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.posts.create'));
        $response->assertStatus(404);
    }

    public function test_store_success(): void
    {
        // set up category
        $category = Category::factory()->create();

        // store post
        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Test Post',
                'slug' => 'test-post',
                'category_id' => $category->id,
                'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'image' => UploadedFile::fake()->image('test-image.jpg')
            ]);

        // assertion
        $response->assertStatus(302)
            ->assertRedirectToRoute('admin.posts.index')
            ->assertSessionHas('success', 'Post created successfully!');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'user_id' => $this->admin->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $postData = Post::where('title', 'Test Post')->first();
        $this->assertTrue(Storage::disk('public')->exists($postData->image));
    }

    public function test_store_failed_empty_data(): void
    {
        $data = [
            'title' => '',
            'slug' => '',
            'category_id' => '',
            'body' => ''
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHasErrors([
                'title' => 'The title field is required.',
                'category_id' => 'The category id field is required.',
                'body' => 'The body field is required.',
                'image' => 'The image field is required.'
            ]);
    }

    public function test_store_failed_throw_exception(): void
    {
        // set up category
        $category = Category::factory()->create();

        // set up mocking database error
        $this->mock(PostService::class, function ($mock) {
            $mock->shouldReceive('createPost')
                ->once()
                ->andThrow(new Exception('Database error unexpected'));
        });

        // store data post
        $response = $this->actingAs($this->admin)
            ->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), [
                'title' => 'Test Post',
                'slug' => 'test-post',
                'category_id' => $category->id,
                'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'image' => UploadedFile::fake()->image('test-image.jpg')
            ]);

        // assertion
        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Post create failed!');

        $this->assertDatabaseMissing('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
        ]);
    }

    public function test_edit_success(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->admin->id
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.posts.edit', $post));
        $response->assertStatus(200);
    }

    public function test_edit_failed_not_login(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->admin->id
        ]);
        $response = $this->get(route('admin.posts.edit', $post));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_edit_failed_wrong_role_users(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->admin->id
        ]);
        $response = $this->actingAs(User::factory()->create())->get(route('admin.posts.edit', $post));
        $response->assertStatus(404);
    }

    public function test_update_post_to_published_success()
    {
        $post = Post::factory()->pending()->create();

        $response = $this->actingAs($this->admin)
            ->from(route('admin.posts.edit', $post))
            ->put(route('admin.posts.update', $post), [
                'is_published' => true
            ]);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('success', 'Post approved successfully!');
    }

    // public function test_update_post_to_published_failed()
    // {
    //     // set up data post
    //     $post = Post::factory()->pending()->create();

    //     // set up mocking database error
    //     $this->mock(PostService::class, function ($mock) {
    //         $mock->shouldReceive('updatePost')
    //             ->once()
    //             ->andThrow(new Exception('Database error unexpected'));
    //     });

    //     $response = $this->actingAs($this->admin)
    //         ->from(route('admin.posts.edit', $post))
    //         ->put(route('admin.posts.update', $post), [
    //             'is_published' => true
    //         ]);


    //     $response->assertStatus(302)
    //         ->assertRedirectBack()
    //         ->assertSessionHas('error', 'Post approve failed!');
    // }

    // public function test_update_success_without_changing_image(): void
    // {
    //     // set up category
    //     $category = Category::factory()->create();

    //     // set up existing image
    //     // $existingImage = 'post-images/existing-image.jpg';
    //     // $file = UploadedFile::fake()->image('existing-image.jpg');
    //     // $file->storeAs('post-images', 'existing-image.jpg', 'public');
    //     // set up old post data
    //     $post = Post::factory()->create([
    //         'title' => 'Test Post',
    //         'slug' => 'test-post',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
    //         'image' => UploadedFile::fake()->image('test-image.jpg')
    //     ]);

    //     // update post data
    //     $response = $this->actingAs($this->admin)
    //         ->put(route('admin.posts.update', $post), [
    //             'title' => 'Test Post Update',
    //             'user_id' => $this->admin->id,
    //             'category_id' => $category->id,
    //             'slug' => 'test-post-update',
    //             'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //         ]);

    //     // assertion
    //     $response->assertStatus(302)
    //         ->assertRedirectToRoute('admin.posts.index')
    //         ->assertSessionHas('success', 'Post updated successfully!');

    //     $this->assertDatabaseHas('posts', [
    //         'title' => 'Test Post Update',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'slug' => 'test-post-update',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //     ]);

    //     $this->assertDatabaseMissing('posts', [
    //         'title' => 'Test Post',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'slug' => 'test-post',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //     ]);
    // }

    // public function test_update_failed_throw_exception(): void
    // {
    //     $category = Category::factory()->create();

    //     $post = Post::factory()->create([
    //         'title' => 'Test Post',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'slug' => 'test-post',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //     ]);

    //     $data = [
    //         'title' => 'Test Post Update',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'slug' => 'test-post-update',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //     ];

    //     $this->mock(PostService::class, function ($mock) {
    //         $mock->shouldReceive('updatePost')
    //             ->once()
    //             ->andThrow(new Exception('Database error unexpected'));
    //     });

    //     $response = $this->actingAs($this->admin)
    //         ->from(route('admin.posts.edit', $post))
    //         ->put(route('admin.posts.update', $post), $data);

    //     $response->assertStatus(302)
    //         ->assertRedirectBack()
    //         ->assertSessionHas('error', 'Post update failed!');

    //     $this->assertDatabaseMissing('posts', [
    //         'title' => 'Test Post Update',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'slug' => 'test-post-update',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //     ]);

    //     $this->assertDatabaseHas('posts', [
    //         'title' => 'Test Post',
    //         'user_id' => $this->admin->id,
    //         'category_id' => $category->id,
    //         'slug' => 'test-post',
    //         'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
    //     ]);
    // }

    public function test_destroy_success(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.posts.destroy', $post));

        $response->assertStatus(302)
            ->assertRedirectToRoute('admin.posts.index')
            ->assertSessionHas('success', 'Post deleted successfully!');

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    public function test_destroy_success_failed_throw_exception(): void
    {
        $post = Post::factory()->create();

        $this->mock(PostService::class, function ($mock) {
            $mock->shouldReceive('removePost')
                ->once()
                ->andThrow(new Exception('Database error unxepected'));
        });

        $response = $this->actingAs($this->admin)
            ->from(route('admin.posts.index'))
            ->delete(route('admin.posts.destroy', $post));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Post delete failed!');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);
    }
}
