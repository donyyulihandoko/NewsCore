<?php

namespace Tests\Feature\Repositories;

use App\Models\Category;
use App\Models\Post;
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
        $result = $this->categoryRepository->getCategoriesPagination(9);
        $this->assertNotNull($result);
        $this->assertCount(9, $result);
        $this->assertEquals(20, $result->total());
    }

    public function test_create_category()
    {
        $data = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => 'category-images/test-category.jpg'
        ];

        $result = $this->categoryRepository->createCategory($data);

        $this->assertNotNull($result);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => 'category-images/test-category.jpg'
        ]);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => 'category-images/test-category.jpg'
        ]);

        $data = [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update',
            'image' => 'category-images/test-update-category.jpg'
        ];

        $this->categoryRepository->updateCategory($category, $data);

        $this->assertDatabaseMissing('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Description Test Category',
            'image' => 'category-images/test-category.jpg'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category Update',
            'slug' => 'test-category-update',
            'description' => 'Description Test Category Update',
            'image' => 'category-images/test-update-category.jpg'
        ]);
    }

    public function test_remove_category()
    {
        $category = Category::factory()->create();

        $this->categoryRepository->removeCategory($category);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    public function test_find_by_slug()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category'
        ]);
        $result = $this->categoryRepository->findBySlug($category);

        $this->assertNotNull($result);
        $this->assertEquals($category->name, $result->name);
    }

    public function test_total_categories()
    {
        Category::factory(10)->create();
        $result = $this->categoryRepository->totalCategories();

        $this->assertEquals(10, $result);
    }

    public function test_get_categories()
    {
        Category::factory(20)->create();
        $result = $this->categoryRepository->getCategories();

        $this->assertCount(20, $result);
    }

    public function test_get_categories_with_total_published_post()
    {
        $category = Category::factory()->create();
        Post::factory(10)->published()->recycle($category)->create();

        $result = $this->categoryRepository->getCategoriesWithTotalPublishedPosts(10);

        $this->assertCount(1, $result);

        $result->each(function ($category) {
            $this->assertEquals(10, $category->posts_count);
        });
    }
}
