<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Partnership;
use App\Models\Product;
use App\Models\Review;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheInvalidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_product_saves_and_deletes_update_homepage_and_katalog_cache_version(): void
    {
        // 1. Initial State
        $this->assertNull(Cache::get('homepage_products_version'));
        $this->assertNull(Cache::get('katalog_cache_version'));

        // Create dependencies
        $type = Type::create([
            'name_id' => 'Makanan',
            'name_en' => 'Food',
        ]);
        
        $category = Category::create([
            'type_id' => $type->id,
            'name_id' => 'Kue',
            'name_en' => 'Cake',
        ]);

        // 2. Create Product
        $product = Product::create([
            'name_id' => 'Kue Lumpur',
            'name_en' => 'Lumpur Cake',
            'product_type' => $type->id,
            'category_type' => $category->id,
            'price' => 15000,
            'status' => 'Aktif',
        ]);

        $createTime1 = Cache::get('homepage_products_version');
        $createTime2 = Cache::get('katalog_cache_version');

        $this->assertNotNull($createTime1);
        $this->assertNotNull($createTime2);

        // Sleep to ensure time() changes if needed, or manually override time() or simply wait.
        // Usually, time() will be the same if it runs instantly, so let's verify it gets set.
        
        // 3. Update Product
        sleep(1);
        $product->update(['price' => 20000]);

        $updateTime1 = Cache::get('homepage_products_version');
        $updateTime2 = Cache::get('katalog_cache_version');

        $this->assertGreaterThan($createTime1, $updateTime1);
        $this->assertGreaterThan($createTime2, $updateTime2);

        // 4. Delete Product
        sleep(1);
        $product->delete();

        $deleteTime1 = Cache::get('homepage_products_version');
        $deleteTime2 = Cache::get('katalog_cache_version');

        $this->assertGreaterThan($updateTime1, $deleteTime1);
        $this->assertGreaterThan($updateTime2, $deleteTime2);
    }

    public function test_type_saves_and_deletes_update_katalog_version_and_forget_hot_deals(): void
    {
        // Set hot deals cache
        Cache::put('homepage:hot_deals', 'some-deals-data', 600);
        $this->assertEquals('some-deals-data', Cache::get('homepage:hot_deals'));

        $this->assertNull(Cache::get('katalog_cache_version'));

        // 1. Create Type
        $type = Type::create([
            'name_id' => 'Elektronik',
            'name_en' => 'Electronics',
        ]);

        $createVersion = Cache::get('katalog_cache_version');
        $this->assertNotNull($createVersion);
        $this->assertNull(Cache::get('homepage:hot_deals')); // forgotten!

        // Re-set hot deals cache
        Cache::put('homepage:hot_deals', 'some-deals-data', 600);

        // 2. Update Type
        sleep(1);
        $type->update(['name_id' => 'Elektronik Baru']);

        $updateVersion = Cache::get('katalog_cache_version');
        $this->assertGreaterThan($createVersion, $updateVersion);
        $this->assertNull(Cache::get('homepage:hot_deals')); // forgotten!

        // Re-set hot deals cache
        Cache::put('homepage:hot_deals', 'some-deals-data', 600);

        // 3. Delete Type
        sleep(1);
        $type->delete();

        $deleteVersion = Cache::get('katalog_cache_version');
        $this->assertGreaterThan($updateVersion, $deleteVersion);
        $this->assertNull(Cache::get('homepage:hot_deals')); // forgotten!
    }

    public function test_category_saves_and_deletes_update_katalog_version(): void
    {
        $type = Type::create([
            'name_id' => 'Pakaian',
            'name_en' => 'Clothes',
        ]);

        Cache::forget('katalog_cache_version');

        $this->assertNull(Cache::get('katalog_cache_version'));

        // 1. Create Category
        $category = Category::create([
            'type_id' => $type->id,
            'name_id' => 'Kaos',
            'name_en' => 'T-Shirt',
        ]);

        $createVersion = Cache::get('katalog_cache_version');
        $this->assertNotNull($createVersion);

        // 2. Update Category
        sleep(1);
        $category->update(['name_id' => 'Kemeja']);

        $updateVersion = Cache::get('katalog_cache_version');
        $this->assertGreaterThan($createVersion, $updateVersion);

        // 3. Delete Category
        sleep(1);
        $category->delete();

        $deleteVersion = Cache::get('katalog_cache_version');
        $this->assertGreaterThan($updateVersion, $deleteVersion);
    }

    public function test_banner_saves_and_deletes_forget_hero_banner(): void
    {
        Cache::put('homepage:hero_banner', 'banner-data', 600);
        $this->assertEquals('banner-data', Cache::get('homepage:hero_banner'));

        // 1. Create Banner
        $banner = Banner::create([
            'media_url' => 'banner1.jpg',
            'media_type' => 'image',
        ]);

        $this->assertNull(Cache::get('homepage:hero_banner')); // forgotten!

        // Re-set
        Cache::put('homepage:hero_banner', 'banner-data', 600);

        // 2. Update Banner
        $banner->update(['media_url' => 'banner2.jpg']);
        $this->assertNull(Cache::get('homepage:hero_banner')); // forgotten!

        // Re-set
        Cache::put('homepage:hero_banner', 'banner-data', 600);

        // 3. Delete Banner
        $banner->delete();
        $this->assertNull(Cache::get('homepage:hero_banner')); // forgotten!
    }

    public function test_partnership_saves_and_deletes_forget_partners(): void
    {
        Cache::put('homepage:partners', 'partner-data', 600);
        $this->assertEquals('partner-data', Cache::get('homepage:partners'));

        // 1. Create Partnership
        $partner = Partnership::create([
            'category' => 'BUMN',
            'image_url' => 'logo.png',
        ]);

        $this->assertNull(Cache::get('homepage:partners')); // forgotten!

        // Re-set
        Cache::put('homepage:partners', 'partner-data', 600);

        // 2. Update Partnership
        $partner->update(['image_url' => 'logo2.png']);
        $this->assertNull(Cache::get('homepage:partners')); // forgotten!

        // Re-set
        Cache::put('homepage:partners', 'partner-data', 600);

        // 3. Delete Partnership
        $partner->delete();
        $this->assertNull(Cache::get('homepage:partners')); // forgotten!
    }

    public function test_review_saves_and_deletes_forget_reviews(): void
    {
        Cache::put('homepage:reviews', 'reviews-data', 600);
        $this->assertEquals('reviews-data', Cache::get('homepage:reviews'));

        // 1. Create Review
        $review = Review::create([
            'name' => 'John Doe',
            'review_id' => 'Sangat bagus!',
            'review_en' => 'Very good!',
            'star' => 5,
            'review_date' => now(),
        ]);

        $this->assertNull(Cache::get('homepage:reviews')); // forgotten!

        // Re-set
        Cache::put('homepage:reviews', 'reviews-data', 600);

        // 2. Update Review
        $review->update(['star' => 4]);
        $this->assertNull(Cache::get('homepage:reviews')); // forgotten!

        // Re-set
        Cache::put('homepage:reviews', 'reviews-data', 600);

        // 3. Delete Review
        $review->delete();
        $this->assertNull(Cache::get('homepage:reviews')); // forgotten!
    }
}