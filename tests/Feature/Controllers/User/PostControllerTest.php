<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->user()->create();
    }

    public function test_index_success()
    {
        $response = $this->actingAs($this->user)->get(route('posts.index'));
        $response->assertStatus(200);
    }

    public function test_index_failed_not_login()
    {
        $response = $this->get(route('posts.index'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_index_failed_wrong_role_users()
    {
        $response = $this->actingAs(User::factory()->admin()->create())->get(route('posts.index'));
        $response->assertStatus(404);
    }

    public function test_show_success()
    {
        $post = Post::factory()->create();
        $response = $this->actingAs($this->user)->get(route('posts.show', $post));
        $response->assertStatus(200);
    }

    public function test_show_failed_not_login()
    {
        $post = Post::factory()->create();
        $response = $this->get(route('posts.show', $post));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_show_failed_wrong_role_users()
    {
        $post = Post::factory()->create();
        $response = $this->actingAs(User::factory()->admin()->create())->get(route('posts.show', $post));
        $response->assertStatus(404);
    }
}
