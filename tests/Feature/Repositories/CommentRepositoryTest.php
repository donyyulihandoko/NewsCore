<?php

namespace Tests\Feature\Repositories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Override;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CommentRepository $commentRepository;
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->commentRepository = $this->app->make(CommentRepository::class);
    }

    public function test_service_container_not_null()
    {
        $this->assertNotNull($this->commentRepository);
    }

    public function test_get_comments()
    {
        $comments = $this->commentRepository->getComments();
        $this->assertNotNull($comments);
    }

    public function test_add_comment()
    {
        $post = Post::factory()->create();
        $user = User::factory()->user()->create();
        $data = [
            'body' => 'This is a test comment.',
        ];

        $comment = $this->actingAs($user)->commentRepository->addComment($post, $data);
        $this->assertNotNull($comment);
        $this->assertEquals($data['body'], $comment->body);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
    }

    public function test_remove_comment()
    {
        $post = Post::factory()->create();
        $user = User::factory()->user()->create();
        $comment = $this->actingAs($user)->commentRepository->addComment($post, ['body' => 'This is a test comment.']);

        $this->actingAs($user)->commentRepository->removeComment($comment);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_total_comments(){
        Comment::factory(10)->create();
        $result = $this->commentRepository->totalComments();

        $this->assertEquals(10, $result);
    }

    public function test_delete_comment_by_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->user()->create();
        $comment = $this->actingAs($user)->commentRepository->addComment($post, ['body' => 'This is a test comment.']);

        $this->actingAs($user)->commentRepository->deleteCommentByUser($comment);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}

