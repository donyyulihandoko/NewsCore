<?php

namespace Tests\Feature\Controllers\Author;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Services\PostService;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Database\QueryException;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $author;

    public function setUp(): void
    {
        parent::setUp();
        $this->author = User::factory()->author()->create();
        Storage::fake('public');
    }

    public function test_index_success(): void
    {
        $response = $this->actingAs($this->author)->get(route('author.posts.index'));
        $response->assertStatus(200);
    }

    public function test_index_failed_not_login(): void
    {
        $response = $this->get(route('author.posts.index'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_index_failed_wrong_role_users(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('author.posts.index'));
        $response->assertStatus(404);
    }

    public function test_create_success(): void
    {
        $response = $this->actingAs($this->author)->get(route('author.posts.create'));
        $response->assertStatus(200);
    }

    public function test_create_failed_not_login(): void
    {
        $response = $this->get(route('author.posts.create'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_create_failed_wrong_role_users(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('author.posts.create'));
        $response->assertStatus(404);
    }

    public function test_store_success(): void
    {
        // set up category
        $category = Category::factory()->create();

        // store post
        $response = $this->actingAs($this->author)
            ->post(route('author.posts.store'), [
                'title' => 'Test Post',
                'slug' => 'test-post',
                'category_id' => $category->id,
                'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'image' => UploadedFile::fake()->image('test-image.jpg')
            ]);

        // assertion
        $response->assertStatus(302)
            ->assertRedirectToRoute('author.posts.index')
            ->assertSessionHas('success', 'Post created successfully!');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'slug' => 'test-post',
            'category_id' => $category->id,
            'user_id' => $this->author->id,
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

        $response = $this->actingAs($this->author)
            ->from(route('author.posts.create'))
            ->post(route('author.posts.store'), $data);

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
        $this->mock(PostService::class, function ($mock) use ($category) {
            $mock->shouldReceive('createPost')
                ->once()
                ->andThrow(new QueryException(
                    'mysql',                           // Nama koneksi database
                    'insert into posts (title, slug, category_id, user_id, body, image) values (?, ?, ?, ?, ?, ?)', // Raw SQL tiruan
                    ['Test Post', 'test-post', $category->id, $this->author->id, 'Lorem ipsum dolor sit amet consectetur adipisicing elit.', 'test-image.jpg'],                    // Bindings
                    new \Exception('Database constraint error')
                ));
        });

        // store data post
        $response = $this->actingAs($this->author)
            ->from(route('author.posts.create'))
            ->post(route('author.posts.store'), [
                'title' => 'Test Post',
                'slug' => 'test-post',
                'category_id' => $category->id,
                'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'image' => UploadedFile::fake()->image('test-image.jpg')
            ]);

        // assertion
        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');

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
            'user_id' => $this->author->id
        ]);

        $response = $this->actingAs($this->author)->get(route('author.posts.edit', $post));
        $response->assertStatus(200);
    }

    public function test_edit_failed_not_login(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id
        ]);
        $response = $this->get(route('author.posts.edit', $post));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_edit_failed_wrong_role_users(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id
        ]);
        $response = $this->actingAs(User::factory()->create())->get(route('author.posts.edit', $post));
        $response->assertStatus(404);
    }

    public function test_update_success_without_changing_image(): void
    {
        // set up category
        $category = Category::factory()->create();

        // set up old post data
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => UploadedFile::fake()->image('test-image.jpg')
        ]);

        // update post data
        $response = $this->actingAs($this->author)
            ->put(route('author.posts.update', $post), [
                'title' => 'Test Post Update',
                'user_id' => $this->author->id,
                'category_id' => $category->id,
                'slug' => 'test-post-update',
                'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
            ]);

        // assertion
        $response->assertStatus(302)
            ->assertRedirectToRoute('author.posts.index')
            ->assertSessionHas('success', 'Post updated successfully!');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post Update',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post-update',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Test Post',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        // $postData = Post::where('title', 'Test Post Update')->first();
        // $this->assertTrue(Storage::disk('public')->exists($postData->image));
    }

    public function test_update_success_with_changing_image(): void
    {
        // set up old image
        $oldImage = 'post-images/old-image.jpg';
        Storage::disk('public')->put($oldImage, UploadedFile::fake()->image('old-image.jpg')->getContent());
        
        // set up category
        $category = Category::factory()->create();

        // set up old post data
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'image' => $oldImage
        ]);

        // set up new image
        $newImage = UploadedFile::fake()->image('new-image.jpg');
        // update post data
        $response = $this->actingAs($this->author)
            ->put(route('author.posts.update', $post), [
                'title' => 'Test Post Update',
                'user_id' => $this->author->id,
                'category_id' => $category->id,
                'slug' => 'test-post-update',
                'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'image' => $newImage
            ]);

        // assertion
        $response->assertStatus(302)
            ->assertRedirectToRoute('author.posts.index')
            ->assertSessionHas('success', 'Post updated successfully!');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post Update',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post-update',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertDatabaseMissing('posts', [
            'title' => 'Test Post',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertFalse(Storage::disk('public')->exists($oldImage));
        $this->assertTrue(Storage::disk('public')->exists('post-images/' . $newImage->hashName()));
    }


    public function test_update_failed_throw_exception(): void
    {
        $category = Category::factory()->create();

        $post = Post::factory()->create([
            'title' => 'Test Post',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $data = [
            'title' => 'Test Post Update',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post-update',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ];

        $this->mock(PostService::class, function ($mock) use ($post) {
            $mock->shouldReceive('updatePost')
                ->once()
                ->andThrow(
                    new QueryException(
                        'mysql',                           // Nama koneksi database
                        'update posts set title = ? where id = ?', // Raw SQL tiruan
                        ['Test Post Update', $post->id],    // Bindings
                        new \Exception('Database constraint error')
                    )
                );
        });

        $response = $this->actingAs($this->author)
            ->from(route('author.posts.edit', $post))
            ->put(route('author.posts.update', $post), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');

        $this->assertDatabaseMissing('posts', [
            'title' => 'Test Post Update',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post-update',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $this->author->id,
            'category_id' => $category->id,
            'slug' => 'test-post',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
        ]);
    }

    public function test_destroy_success(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id
        ]);

        $response = $this->actingAs($this->author)
            ->from(route('author.posts.index'))
            ->delete(route('author.posts.destroy', $post));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('success', 'Post deleted successfully!');

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    public function test_destroy_success_failed_throw_exception(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->author->id
        ]);

        $this->mock(PostService::class, function ($mock) use ($post) {
            $mock->shouldReceive('removePost')
                ->once()
                ->andThrow(
                    new QueryException(
                        'mysql',                           // Nama koneksi database
                        'delete from posts where id = ?', // Raw SQL tiruan
                        [$post->id],                    // Bindings
                        new \Exception('Database constraint error')
                    )
                );
        });

        $response = $this->actingAs($this->author)
            ->from(route('author.posts.index'))
            ->delete(route('author.posts.destroy', $post));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);
    }
}
