<?php

namespace Tests\Feature\Controllers\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\CategoryService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        Storage::fake('public');
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
        // set up image
        $image = UploadedFile::fake()->image('test-image.jpg');

        // create data
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => $image
        ]);

        // asserting
        $response->assertStatus(302)
            ->assertRedirectToRoute('admin.categories.index')
            ->assertSessionHas('success', 'Category created successfully!');

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $categoryData = Category::where('name', 'Test Category')->first();
        $this->assertTrue(Storage::disk('public')->exists($categoryData->image));
    }

    public function test_store_failed_empty_data()
    {
        // set up store data
        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), [
                'name' => '',
                'slug' => '',
                'description' => '',
                'image' => ''
            ]);

        // asserting
        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHasErrors([
                'name' => 'The name field is required.',
                'image' => 'The image field is required.'
            ]);
    }

    public function test_store_failed_duplicate_data_name()
    {
        // set up existing data
        Category::factory()->create([
            'name' => 'Test Category'
        ]);

        // set up store data
        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), [
                'name' => 'Test Category',
                'slug' => 'test-category',
                'description' => 'Description Test Category',
                'image' => UploadedFile::fake()->image('test-image.jpg')
            ]);

        // asserting
        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHasErrors([
                'name' => 'The name has already been taken.'
            ]);
    }

    public function test_store_failed_throw_exception()
    {
        // set up mocking exception
        $this->mock(CategoryService::class, function ($mock) {
            $mock->shouldReceive('createCategory')
                ->once()
                ->andThrow(new Exception('Database error unexpected'));
        });

        // set up store data
        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), [
                'name' => 'Test Category',
                'slug' => 'test-category',
                'description' => 'Description Test Category',
                'image' => UploadedFile::fake()->image('test-image.jpg')
            ]);

        // assertion
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

    public function test_update_success_without_changing_image()
    {
        // setup old image
        $oldImage = 'category-images/existing-icon.jpg';
        $file = UploadedFile::fake()->image('existing-icon.jpg');
        $file->storeAs('category-images', 'existing-icon.jpg', 'public');

        // set up old data category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => $oldImage
        ]);

        // update data
        $response = $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $category), [
                'name' => 'Test Category Update',
                'slug' => 'test-category-update',
                'description' => 'Description Test Category Update'
            ]);

        // assertion
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

        $this->assertTrue(Storage::disk('public')->exists($oldImage));
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

        $this->mock(CategoryService::class, function ($mock) {
            $mock->shouldReceive('updateCategory')
                ->once()
                ->andThrow(new Exception('Database error unexpected'));
        });

        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.edit', $category))
            ->put(route('admin.categories.update', $category), [
                'name' => 'Test Category Update',
                'slug' => 'test-category-update',
                'description' => 'Description Test Category Update'
            ]);

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

    public function test_destroy_failed_associated_post()
    {
        // set up category
        $category = Category::factory()->create();
        Post::factory()->create([
            'category_id' => $category->id
        ]);

        // destroy
        $response = $this->actingAs($this->admin)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $category));

        // assertion
        $response->assertStatus(302)
            ->assertRedirectBack()
            ->assertSessionHas('error', 'The category cannot be deleted because it still contains associated post.');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id
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
