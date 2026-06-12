<?php

namespace Tests\Feature\Services;

use App\Services\CommentService;
use App\Services\Impl\CommentServiceImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Override;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;
    
    private CommentService $commentService;
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->commentService = $this->app->make(CommentService::class);
    }

    public function test_service_container_not_null()
    {
        $this->assertNotNull($this->commentService);
    }

    public function test_service_container_instance_of()
    {
        $this->assertInstanceOf(CommentServiceImpl::class, $this->commentService);
    }

    public function test_get_comments()
    {
        $comments = $this->commentService->getComments();
        $this->assertNotNull($comments);
    }

    public function test_total_comments()
    {
        $totalComments = $this->commentService->totalComments();
        $this->assertIsInt($totalComments);
    }

    public function test_remove_comment()
    {
        $comment = \App\Models\Comment::factory()->create();
        $result = $this->commentService->removeComment($comment);
        $this->assertEquals(1, $result);
    }

    public function test_delete_comment_by_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->user()->create();
        $comment = $this->actingAs($user)->commentService->addComment($post, ['body' => 'This is a test comment.']);
        
        $result = $this->actingAs($user)->commentService->deleteCommentByUser($comment);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_add_comment()
    {
        $post = Post::factory()->create();
        $user = User::factory()->user()->create();
        $data = [
            'body' => 'This is a test comment'
        ];
        $comment = $this->actingAs($user)->commentService->addComment($post, $data);
        $this->assertNotNull($comment);
        $this->assertEquals('This is a test comment', $comment->body);
        $this->assertEquals($user->id, $comment->user_id);
    }
}
