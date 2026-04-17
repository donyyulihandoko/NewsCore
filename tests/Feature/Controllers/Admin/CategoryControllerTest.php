<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_index_success()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
    }

    public function test_index_failed_not_login()
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_create_success()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.create'));
        $response->assertStatus(200);
    }

    public function test_create_failed_not_login()
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_store_success()
    {
        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ];
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), $data);

        $response->assertStatus(302)
            ->assertRedirectToRoute('admin.categories.index')
            ->assertSessionHas('success', 'Category created successfully!');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_store_failed_empty_data()
    {
        $data = [
            'name' => '',
            'slug' => '',
            'description' => ''
        ];
        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHasErrors([
                'name' => 'The name field is required.',
                'slug' => 'The slug field is required.',
                'description' => 'The description field is required.'
            ]);
    }

    public function test_store_failed_duplicate_data_name()
    {
        Category::factory()->create([
            'name' => 'Test Category'
        ]);

        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHasErrors([
                'name' => 'The name has already been taken.'
            ]);
    }

    public function test_store_failed_throw_exception()
    {
        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ];
        $this->mock(CategoryService::class, function ($mock) {
            $mock->shouldReceive('createCategory')
                ->once()
                ->andThrow(new Exception('Database error unexpected'));
        });

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Category create failed!');
    }

    public function test_edit_success()
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.categories.edit', $category));

        $response->assertStatus(200);
    }

    public function test_edit_failed_not_login()
    {
        $category = Category::factory()->create();
        $response = $this->get(route('admin.categories.edit', $category));

        $response->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    public function test_update_success()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $data = [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), $data);

        $response->assertStatus(302)
            ->assertRedirectToRoute('admin.categories.index')
            ->assertSessionHas('success', 'Category updated successfully!');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ]);

        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_update_failed_empty_data()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $data = [
            'name' => '',
            'slug' => '',
            'description' => ''
        ];

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.edit', $category))
            ->put(route('admin.categories.update', $category), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHasErrors([
                'name' => 'The name field is required.',
                'slug' => 'The slug field is required.',
                'description' => 'The description field is required.'
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_update_failed_throw_exception()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $data = [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ];

        $this->mock(CategoryService::class, function ($mock) {
            $mock->shouldReceive('updateCategory')
                ->once()
                ->andThrow(new Exception('Database error unexpected'));
        });

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.edit', $category))
            ->put(route('admin.categories.update', $category), $data);

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Category update failed!');

        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_destroy_success()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('success', 'Category deleted successfully!');

        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_destroy_failed_throw_exception()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $this->mock(CategoryService::class, function ($mock) {
            $mock->shouldReceive('removeCategory')
                ->once()
                ->andThrow(new Exception('Database error unexpected'));
        });

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $category));

        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'Category delete failed!');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }
}
