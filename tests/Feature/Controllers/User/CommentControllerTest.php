<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Database\QueryException;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->user()->create();
    }

    /**
     * Test the store method of CommentController.
     *
     * @return void
     */

    public function test_store_comment(): void
    {
        $post = Post::factory()->published()->create();

        $response = $this->actingAs($this->user)
            ->post(route('posts.comments.store', $post), [
            'body' => $this->faker->sentence,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Comment added successfully.');
    }

    public function test_store_comment_validation_error(): void
    {
        $post = Post::factory()->published()->create();

        $response = $this->actingAs($this->user)
            ->post(route('posts.comments.store', $post), [
            'body' => '', // Empty body to trigger validation error
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_store_failed_throw_exception(): void
    {
        $post = Post::factory()->published()->create();

        // Simulate a failure in the addComment method
        $this->mock(CommentService::class, function ($mock) use ($post) {
            $mock->shouldReceive('addComment')
                ->once()
                ->andThrow(new QueryException('mysql',                           // Nama koneksi database
                    'insert into comments (post_id, user_id, body) values (?, ?, ?)', // Raw SQL tiruan
                    [$post->id, $this->user->id, $this->faker->sentence],                    // Bindings
                    new \Exception('Database constraint error') // Previous exception
                    ));
        });
    
        $response = $this->actingAs($this->user)
            ->post(route('posts.comments.store', $post), [
            'body' => $this->faker->sentence,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');
    }
    
    public function test_destroy_comment(): void
    {
        $post = Post::factory()->published()->create();
        $comment = $post->comments()->create([
            'body' => $this->faker->sentence,
            'user_id' => $this->user->id,
        ]);
        
        $response = $this->actingAs($this->user)
            ->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Comment deleted successfully.');
    }

    // public function test_destroy_comment_unauthorized(): void
    // {
    //     $post = Post::factory()->published()->create();
    //     $comment = Comment::factory()->create([
    //         'post_id' => $post->id,
    //         'user_id' => User::factory()->user()->create()->id, // Comment by another user
    //     ]);

    //     $response = $this->actingAs($this->user)
    //         ->delete(route('comments.destroy', $comment));

    //     $response->assertStatus(403); // Forbidden
    // }

    public function test_destroy_comment_not_found(): void
    {
        $response = $this->actingAs($this->user)
            ->delete(route('comments.destroy', 999)); // Non-existent comment ID

        $response->assertStatus(404); // Not Found
    }

    public function test_destroy_failed_throw_exception(): void
    {
        $post = Post::factory()->published()->create();
        $comment = $post->comments()->create([
            'body' => $this->faker->sentence,
            'user_id' => $this->user->id,
        ]);

        $this->mock(CommentService::class, function ($mock) use ($comment) {
            $mock->shouldReceive('deleteCommentByUser')
                ->once()
                ->andThrow(new QueryException('mysql',                           // Nama koneksi database
                    'delete from comments where id = ?', // Raw SQL tiruan
                    [$comment->id],                    // Bindings
                    new \Exception('Database constraint error') // Previous exception
                    ));
        });

        $response = $this->actingAs($this->user)
            ->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');
    }
}
