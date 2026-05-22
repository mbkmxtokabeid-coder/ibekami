<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductSection extends Component
{
    public int $page = 1;
    public int $perPage = 4;
    public int $maxItems = 12;
    public array $products = [];
    public int $totalProducts = 0;
    public int $maxPages = 1;

    public function mount(): void
    {
        $this->loadProducts();
    }

    public function setPerPage(int $value): void
    {
        $this->perPage = $value;
        $this->page = 1;

        // Adjust maxItems based on perPage
        if ($value == 8) {
            $this->maxItems = 16;
        } else {
            $this->maxItems = 12;
        }

        $this->loadProducts();
    }

    public function nextPage(): void
    {
        if ($this->page < $this->maxPages) {
            $this->page++;
            $this->loadProducts();
        }
    }

    public function prevPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
            $this->loadProducts();
        }
    }

    public function loadProducts(): void
    {
        $version = \Illuminate\Support\Facades\Cache::rememberForever('homepage_products_version', fn() => time());

        // Get total active products (cache biar TTFB lebih stabil)
        $this->totalProducts = \Illuminate\Support\Facades\Cache::remember("homepage:active_products_count:v{$version}", now()->addMinutes(10), function () {
            return Product::query()
                ->where('status', 'Aktif')
                ->count();
        });
        
        // Calculate max pages
        $this->maxPages = ceil(min($this->totalProducts, $this->maxItems) / $this->perPage);

        // Load products for current page
        // Order by activated_at DESC (terakhir diaktifkan muncul paling depan)
        $cacheKey = sprintf('homepage:products:v%s:%d:%d:%d', $version, $this->page, $this->perPage, $this->maxItems);
        $products = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Product::query()
                ->with(['type', 'category'])
                ->where('status', 'Aktif')
                ->orderBy('activated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->skip(($this->page - 1) * $this->perPage)
                ->take($this->perPage)
                ->get();
        });

        // Map products to array
        $this->products = $products->map(function ($product) {
            return [
                'id' => $product->product_id,
                'name' => $product->name,
                'cat' => $product->type->name ?? $product->category->name ?? 'Produk',
                'img' => $this->getProductImage($product),
                'slug' => $product->getSlug(),
            ];
        })->toArray();
    }

    private function getProductImage($product): string
    {
        return $product->getFirstImageUrl();
    }

    public function render()
    {
        return view('livewire.halaman-utama.product-section');
    }
}
