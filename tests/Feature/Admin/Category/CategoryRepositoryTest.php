<?php

namespace Tests\Feature\Admin\Category;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->app->make(CategoryRepository::class);
    }

    public function test_service_container_not_null()
    {
        self::assertNotNull($this->categoryRepository);
    }

    public function test_get_categories_pagination()
    {
        Category::factory(20)->create();
        $result = $this->categoryRepository->getCategoriesPagination();
        $this->assertNotNull($result);
        $this->assertCount(10, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_create_category()
    {
        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ];
        $this->categoryRepository->createCategory($data);

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

        $this->categoryRepository->updateCategory($category, $data);

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


        $this->categoryRepository->removeCategory($category);

        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category'
        ]);
    }

    public function test_get_categories()
    {
        Category::factory(20)->create();
        $result = $this->categoryRepository->getCategories();

        $this->assertCount(20, $result);
    }
}
