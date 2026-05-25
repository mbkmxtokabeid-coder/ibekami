<?php

namespace App\Livewire\Katalog;

use Livewire\Component;
use App\Models\Product;

class DetailKatalog extends Component
{
    public string $slug = '';
    public ?Product $product = null;
    public array $relatedProducts = [];
    public array $productData = [];
    public int $version = 0;

    public function mount(string $slug = '')
    {
        $this->slug = $slug;
        $this->version = cache()->get('katalog_cache_version', 0);
        
        $redirect = $this->loadProduct();
        if ($redirect) {
            return $redirect;
        }
    }

    public function checkVersion()
    {
        $currentVersion = cache()->get('katalog_cache_version', 0);
        if ($currentVersion > $this->version) {
            $this->version = $currentVersion;
            $redirect = $this->loadProduct();
            if ($redirect) {
                return $redirect;
            }
        }
    }

    private function loadProduct()
    {
        // Generate cache key berdasarkan slug dan locale
        $cacheKey = 'product_detail_' . $this->slug . '_' . app()->getLocale();
        
        // Cache selama 10 menit (600 detik)
        // Detail produk jarang berubah, jadi aman di-cache lebih lama
        $this->product = cache()->remember($cacheKey, 600, function () {
            // Cari produk berdasarkan slug Indonesia atau Inggris (tanpa filter status)
            return Product::with(['type', 'category'])
                ->get()
                ->first(function ($product) {
                    $slugId = \Illuminate\Support\Str::slug($product->name_id);
                    $slugEn = \Illuminate\Support\Str::slug($product->name_en);
                    return $this->slug === $slugId || $this->slug === $slugEn;
                });
        });

        if (!$this->product) {
            abort(404, 'Produk tidak ditemukan');
        }

        // Jika slug yang diakses berbeda dengan slug aktif saat ini, redirect ke slug aktif
        $currentActiveSlug = $this->product->getSlug();
        if ($currentActiveSlug !== $this->slug) {
            return redirect()->route('katalog.detail', ['slug' => $currentActiveSlug]);
        }

        // Load related products
        $this->loadRelatedProducts();
        
        // Prepare product data
        $this->prepareProductData();
    }

    private function loadRelatedProducts(): void
    {
        // Generate cache key untuk related products
        $cacheKey = 'related_products_' . $this->product->product_id . '_' . app()->getLocale();
        
        // Cache selama 10 menit (600 detik)
        $this->relatedProducts = cache()->remember($cacheKey, 600, function () {
            // Load related products tanpa filter status
            return Product::with(['type', 'category'])
                ->where('product_id', '!=', $this->product->product_id)
                ->where(function ($query) {
                    $query->where('category_type', $this->product->category_type)
                          ->orWhere('product_type', $this->product->product_type);
                })
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get()
                ->map(function ($relatedProduct) {
                    return [
                        'name' => $relatedProduct->name,
                        'cat' => $relatedProduct->type->name ?? $relatedProduct->category->name ?? 'Produk',
                        'img' => $this->getProductImage($relatedProduct),
                        'slug' => $relatedProduct->getSlug(),
                        'status' => $relatedProduct->status,
                    ];
                })
                ->toArray();
        });
    }

    private function prepareProductData(): void
    {
        $this->productData = [
            'name' => $this->product->name,
            'category' => $this->product->type->name ?? $this->product->category->name ?? 'Produk',
            'image' => $this->getProductImage($this->product),
            'images' => $this->getAllProductImages($this->product),
            'desc' => $this->product->description ?? 'Produk berkualitas tinggi dengan desain yang menarik dan fungsional.',
            'details' => $this->product->detail ?? [],
            'price' => $this->product->price ?? 0,
            'discount' => $this->product->discount ?? 0,
            'status' => $this->product->status,
        ];
    }

    private function getProductImage($product)
    {
        return $product->getFirstImageUrl();
    }

    private function getAllProductImages($product): array
    {
        return $product->getAllImageUrls();
    }

    public function render()
    {
        return view('livewire.katalog.detail-katalog');
    }
}
