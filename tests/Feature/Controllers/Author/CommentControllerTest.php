<?php

namespace Tests\Feature\Controllers\Author;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Database\QueryException;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = User::factory()->author()->create();
    }

    public function test_store_success(){
        $post = Post::factory()->create();
        $this->actingAs($this->author)->post(route('author.posts.comments.store', $post), [
            'body' => 'This is a test comment.',
        ])->assertRedirect()
            ->assertSessionHas('success', 'Comment added successfully.');
    }

    public function test_store_validation_error(){
        $post = Post::factory()->create();
        $this->actingAs($this->author)->post(route('author.posts.comments.store', $post), [
            'body' => '', // Invalid input
        ])->assertSessionHasErrors('body');
    }
    
    public function test_store_failed_throw_exception()
    {
        $post = Post::factory()->create();

        $this->mock(CommentService::class, function ($mock) use ($post) {
            $mock->shouldReceive('addComment')
                ->once()
                ->andThrow(new QueryException(
                    'mysql',                           // Nama koneksi database
                    'insert into comments (post_id, user_id, body) values (?, ?, ?)', // Raw SQL tiruan
                    [$post->id, $this->author->id, 'This is a test comment.'],                    // Bindings
                    new \Exception('Database constraint error') // Previous exception
                ));
        }); 

        $this->actingAs($this->author)->post(route('author.posts.comments.store', $post), [
            'body' => 'This is a test comment.',
        ])->assertRedirect()
            ->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');
    }

    public function test_destroy_success(){
        $post = Post::factory()->published()->create([
            'user_id' => $this->author->id
        ]);

        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            
        ]);
        $this->actingAs($this->author)->delete(route('author.comments.destroy', $comment))
            ->assertRedirect()
            ->assertSessionHas('success', 'Comment deleted successfully.');
    }

    public function test_destroy_unauthorized(){
        $post = Post::factory()->published()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
        ]);

        $this->actingAs($this->author)->delete(route('author.comments.destroy', $comment))
            ->assertStatus(403);
    }

    public function test_destroy_failed_throw_exception()
    {
        $post = Post::factory()->published()->create([
            'user_id' => $this->author->id
        ]);

        $comment = Comment::factory()->create([
            'post_id' => $post->id,
        ]);

        $this->mock(CommentService::class, function ($mock)  use ($comment) {
            $mock->shouldReceive('deleteCommentByAuthor')
                ->once()
                ->andThrow(new QueryException(
                    'mysql',                           // Nama koneksi database
                    'delete from comments where id = ?', // Raw SQL tiruan
                    [$comment->id],                    // Bindings
                    new \Exception('Database constraint error') // Previous exception
                ));
        }); 

        $this->actingAs($this->author)->delete(route('author.comments.destroy', $comment))
            ->assertRedirect()
            ->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');
    }
}
