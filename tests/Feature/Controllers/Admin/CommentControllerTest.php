<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Comment;
use App\Models\User;
use App\Services\CommentService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Override;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_index_success(){
        
        $response = $this->actingAs($this->admin)
            ->get(route('admin.comments.index'));

        $response->assertStatus(200);
    }


    public function test_destroy_success()
    {
        $comment = Comment::factory()->create();

        $response = $this->actingAs($this->admin)
            ->from(route('admin.comments.index'))
            ->delete(route('admin.comments.destroy', $comment));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('success', 'Comment deleted successfully!');
    }

    public function test_destroy_failed()
    {
        $comment = Comment::factory()->create();

        $this->mock(CommentService::class, function ($mock) use ($comment) {
            $mock->shouldReceive('removeComment')
                ->andThrow(new QueryException(
                        'mysql',                           // Nama koneksi database
                        'delete from comments where id = ?', // Raw SQL tiruan
                        [$comment->id],                    // Bindings
                        new \Exception('Database constraint error') // Previous exception
                ));
        });

        $response = $this->actingAs($this->admin)
            ->from(route('admin.comments.index'))
            ->delete(route('admin.comments.destroy', $comment));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Something went wrong on our end. Contact support if the issue persists.');
    }

}
