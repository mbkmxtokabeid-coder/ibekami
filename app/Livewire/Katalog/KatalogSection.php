<?php

namespace App\Livewire\Katalog;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Models\Type;

class KatalogSection extends Component
{
    public string $activeCategory = '';
    public string $sortBy = '';
    public string $search = '';
    public int $page = 1;
    public int $perPage = 9;
    public ?string $typeFilter = null;

    protected $listeners = [
        'categoryChanged' => 'onCategoryChanged',
        'sortChanged' => 'onSortChanged',
        'searchChanged' => 'onSearchChanged',
    ];

    public function mount(): void
    {
        // Set default values using translation
        $this->activeCategory = __('messages.all_products');
        $this->sortBy = __('messages.newest');
        
        // Check if there's a search parameter in the URL
        if (request()->has('search')) {
            $this->search = request()->get('search');
        }
        
        // Check if there's a type parameter in the URL
        if (request()->has('type')) {
            $typeSlug = request()->get('type');
            $type = Type::all()->first(function ($t) use ($typeSlug) {
                return \Illuminate\Support\Str::slug($t->name) === $typeSlug;
            });
            
            if ($type) {
                $this->activeCategory = $type->name;
                $this->typeFilter = $type->name;
            }
        }
    }

    public function setPerPage(int $count): void
    {
        if ($this->perPage !== $count) {
            $this->perPage = $count;
            $this->page = 1;
        }
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function nextPage(): void
    {
        $this->page++;
    }

    public function previousPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    #[On('categoryChanged')]
    public function onCategoryChanged(string $category): void
    {
        $this->activeCategory = $category;
        $this->typeFilter = null;
        $this->page = 1;
    }

    #[On('sortChanged')]
    public function onSortChanged(string $sort): void
    {
        $this->sortBy = $sort;
        $this->page = 1;
    }

    #[On('searchChanged')]
    public function onSearchChanged(string $search): void
    {
        $this->search = $search;
        $this->page = 1;
    }

    public function resetFilters(): void
    {
        $this->activeCategory = __('messages.all_products');
        $this->sortBy = __('messages.newest');
        $this->search = '';
        $this->typeFilter = null;
        $this->page = 1;
        $this->dispatch('filtersReset');
    }

    protected function getFilteredProducts(): array
    {
        // Generate cache key berdasarkan filter parameters
        $cacheKey = 'katalog_products_' . md5(
            $this->activeCategory . 
            $this->sortBy . 
            $this->search . 
            ($this->typeFilter ?? '') .
            app()->getLocale()
        );
        
        // Cache selama 5 menit (300 detik)
        // Data produk tidak berubah setiap detik, jadi aman di-cache
        return cache()->remember($cacheKey, 300, function () {
            // Query semua produk tanpa filter status
            $query = Product::with(['type', 'category']);

            // Filter berdasarkan type dari URL jika ada
            if ($this->typeFilter) {
                $query->whereHas('type', function ($typeQuery) {
                    $typeQuery->where('name', $this->typeFilter);
                });
            }
            // Filter kategori dari sidebar
            elseif ($this->activeCategory !== __('messages.all_products')) {
                $query->where(function ($q) {
                    $q->whereHas('category', function ($categoryQuery) {
                        $categoryQuery->where('name', 'like', '%' . $this->activeCategory . '%');
                    })->orWhereHas('type', function ($typeQuery) {
                        $typeQuery->where('name', 'like', '%' . $this->activeCategory . '%');
                    });
                });
            }

            // Filter search
            if ($this->search !== '') {
                $query->where('name', 'like', '%' . $this->search . '%');
            }

            // Sort
            switch ($this->sortBy) {
                case __('messages.name_az'):
                case 'A - Z':
                    $query->orderBy('name', 'asc');
                    break;
                case __('messages.name_za'):
                case 'Z - A':
                    $query->orderBy('name', 'desc');
                    break;
                case __('messages.oldest'):
                case 'Terlama':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            return $query->get()->map(function ($product) {
                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'cat' => $product->category->name ?? $product->type->name ?? 'Kategori',
                    'img' => $this->getProductImage($product),
                    'slug' => \Illuminate\Support\Str::slug($product->name),
                    'status' => $product->status,
                ];
            })->toArray();
        });
    }

    private function getProductImage($product)
    {
        $imageUrl = $product->image_url;
        
        if (is_string($imageUrl)) {
            $imageUrl = json_decode($imageUrl, true);
        }
        
        if (!empty($imageUrl) && is_array($imageUrl) && count($imageUrl) > 0) {
            $firstImage = $imageUrl[0];
            if (filter_var($firstImage, FILTER_VALIDATE_URL)) {
                return $firstImage;
            }
            return asset('storage/gambar_produk/' . rawurlencode($firstImage));
        }
        
        return 'https://via.placeholder.com/400x300?text=' . urlencode($product->name);
    }

    public function getPaginatedDataProperty(): array
    {
        $filtered = $this->getFilteredProducts();
        $total = count($filtered);
        $totalPages = ceil($total / $this->perPage);

        if ($this->page > $totalPages && $totalPages > 0) {
            $this->page = $totalPages;
        } elseif ($totalPages == 0) {
            $this->page = 1;
        }

        $sliced = array_slice($filtered, ($this->page - 1) * $this->perPage, $this->perPage);

        return [
            'items' => $sliced,
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $this->page,
        ];
    }

    public function render()
    {
        return view('livewire.katalog.katalog-section');
    }
}
