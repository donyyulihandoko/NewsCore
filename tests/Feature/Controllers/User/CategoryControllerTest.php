<?php

namespace Tests\Feature\Controllers\User;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CategoryControllerTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('topics.index'));
        $response->assertStatus(200);
    }

    public function test_index_failed_not_login()
    {
        $response = $this->get(route('topics.index'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_index_failed_wrong_role_users()
    {
        $response = $this->actingAs(User::factory()->admin()->create())->get(route('topics.index'));
        $response->assertStatus(404);
    }

    public function test_show_success()
    {
        $topic = Category::factory()->create();
        $response = $this->actingAs($this->user)->get(route('topics.show', $topic));
        $response->assertStatus(200);
    }

    public function test_show_failed_not_login()
    {
        $topic = Category::factory()->create();
        $response = $this->get(route('topics.show', $topic));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_show_failed_wrong_role_users()
    {
        $topic = Category::factory()->create();
        $response = $this->actingAs(User::factory()->admin()->create())->get(route('topics.show', $topic));
        $response->assertStatus(404);
    }
}
