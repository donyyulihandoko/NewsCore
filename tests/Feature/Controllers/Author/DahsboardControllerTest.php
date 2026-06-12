<?php

namespace Tests\Feature\Controllers\Author;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DahsboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = User::factory()->author()->create();
    }

    public function test_index_success()
    {
        $response = $this->actingAs($this->author)
            ->get(route('author.dashboard'));

        $response->assertStatus(200);
    }

    public function test_index_failed_not_login()
    {
        $response = $this->get(route('author.dashboard'));

        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_published_post()
    {
        $response = $this->actingAs($this->author)
            ->get(route('author.published.post'));

        $response->assertStatus(200);
    }

    public function test_pending_post()
    {
        $response = $this->actingAs($this->author)
            ->get(route('author.pending.post'));

        $response->assertStatus(200);
    }
}
