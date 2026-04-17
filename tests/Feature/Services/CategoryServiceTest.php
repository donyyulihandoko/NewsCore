<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = $this->app->make(CategoryService::class);
    }

    public function test_service_container_not_null()
    {
        $this->assertNotNull($this->categoryService);
    }

    public function test_get_categories_pagination()
    {
        Category::factory(20)->create();
        $result = $this->categoryService->getCategoriesPagination();
        $this->assertCount(9, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_create_category()
    {
        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ];

        $this->categoryService->createCategory($data);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_update_category()
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

        $this->categoryService->updateCategory($category, $data);

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
    }

    public function test_remove_category()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);

        $this->categoryService->removeCategory($category);

        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_get_categories()
    {
        Category::factory(20)->create();
        $result = $this->categoryService->getCategories();
        $this->assertCount(20, $result);
    }
}
