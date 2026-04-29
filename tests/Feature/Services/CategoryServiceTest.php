<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Post;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = $this->app->make(CategoryService::class);
        Storage::fake('public');
    }

    public function test_service_container_not_null()
    {
        $this->assertNotNull($this->categoryService);
    }

    public function test_get_categories_pagination()
    {
        Category::factory(20)->create();
        $result = $this->categoryService->getCategoriesPagination(9);
        $this->assertCount(9, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_create_category()
    {
        // set up image
        $image = UploadedFile::fake()->image('category_test.jpg');

        // create category
        $result =  $this->categoryService->createCategory([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => $image
        ]);

        // asserting
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $this->assertTrue(Storage::disk('public')->exists($result->image));
    }

    public function test_update_category_without_changing_image()
    {
        // set up  image
        $initialPath = 'category-images/existing-icon.jpg';
        $file = UploadedFile::fake()->image('existing-icon.jpg');
        $file->storeAs('category-images', 'existing-icon.jpg', 'public');

        // set up old category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => $initialPath
        ]);

        // update category
        $this->categoryService->updateCategory($category, [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ]);

        // asserting
        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ]);

        $category->refresh();
        $this->assertTrue(Storage::disk('public')->exists($initialPath));
    }

    public function test_update_category_with_changing_image()
    {
        // set up old image
        $oldImage = 'category-images/existing-icon.jpg';
        $file = UploadedFile::fake()->image('existing-icon.jpg');
        $file->storeAs('category-images', 'existing-icon.jpg', 'public');

        // set up old category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => $oldImage
        ]);

        // set up new image
        $newImage = UploadedFile::fake()->image('newImage.jpg');

        // update category
        $this->categoryService->updateCategory($category, [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update',
            'image' => $newImage
        ]);

        // asserting
        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update'
        ]);

        $category->refresh();
        $this->assertFalse(Storage::disk('public')->exists($oldImage));
        $this->assertTrue(Storage::disk('public')->exists($category->refresh()->image));
    }

    public function test_remove_category()
    {
        $category = Category::factory()->create();

        $this->categoryService->removeCategory($category);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_find_by_slug()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category'
        ]);
        $result = $this->categoryService->findBySlug($category);

        $this->assertNotNull($result);
        $this->assertEquals($category->name, $result->name);
    }



    public function test_total_categories()
    {
        Category::factory(10)->create();
        $result = $this->categoryService->totalCategories();

        $this->assertEquals(10, $result);
    }

    public function test_get_categories()
    {
        Category::factory(20)->create();
        $result = $this->categoryService->getCategories();
        $this->assertCount(20, $result);
    }

    public function test_get_categories_with_total_published_post()
    {
        $category = Category::factory()->create();
        Post::factory(10)->published()->recycle($category)->create();

        $result = $this->categoryService->getCategoriesWithTotalPublishedPosts(10);

        $this->assertCount(1, $result);

        $result->each(function ($category) {
            $this->assertEquals(10, $category->posts_count);
        });
    }
}
