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

    // Multi-select filter
    public array $selectedTypes = [];
    public array $selectedCategories = [];

    protected $listeners = [
        'categoryChanged'    => 'onCategoryChanged',
        'sortChanged'        => 'onSortChanged',
        'searchChanged'      => 'onSearchChanged',
        'multiFilterChanged' => 'onMultiFilterChanged',
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
                return \Illuminate\Support\Str::slug($t->name_id ?: $t->name_en) === $typeSlug;
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

    #[On('multiFilterChanged')]
    public function onMultiFilterChanged(array $types, array $categories): void
    {
        $this->selectedTypes      = $types;
        $this->selectedCategories = $categories;
        $this->typeFilter         = null;
        $this->page               = 1;
    }

    public function resetFilters(): void
    {
        $this->activeCategory     = __('messages.all_products');
        $this->sortBy             = __('messages.newest');
        $this->search             = '';
        $this->typeFilter         = null;
        $this->selectedTypes      = [];
        $this->selectedCategories = [];
        $this->page               = 1;
        $this->dispatch('filtersReset');
    }

    protected function getFilteredProducts(): array
    {
        $cacheKey = 'katalog_products_' . md5(
            $this->activeCategory .
            $this->sortBy .
            $this->search .
            ($this->typeFilter ?? '') .
            implode(',', $this->selectedTypes) .
            implode(',', $this->selectedCategories) .
            app()->getLocale()
        );

        return cache()->remember($cacheKey, 300, function () {
            $query = Product::with(['type', 'category']);

            // Multi-select filter (dari popup) — prioritas tertinggi
            $hasMultiFilter = count($this->selectedTypes) > 0 || count($this->selectedCategories) > 0;

            $typeNameColumn = app()->getLocale() === 'en' ? 'name_en' : 'name_id';
            $categoryNameColumn = app()->getLocale() === 'en' ? 'name_en' : 'name_id';

            if ($hasMultiFilter) {
                $query->where(function ($q) use ($typeNameColumn, $categoryNameColumn) {
                    if (count($this->selectedTypes) > 0) {
                        $q->orWhereHas('type', fn($tq) => $tq->whereIn($typeNameColumn, $this->selectedTypes));
                    }
                    if (count($this->selectedCategories) > 0) {
                        $q->orWhereHas('category', fn($cq) => $cq->whereIn($categoryNameColumn, $this->selectedCategories));
                    }
                });
            }
            // Filter dari URL ?type=
            elseif ($this->typeFilter) {
                $query->whereHas('type', function ($tq) use ($typeNameColumn) {
                    $tq->where($typeNameColumn, $this->typeFilter)
                        ->orWhere(app()->getLocale() === 'en' ? 'name_id' : 'name_en', $this->typeFilter);
                });
            }
            // Filter dari sidebar single-select
            elseif ($this->activeCategory !== __('messages.all_products')) {
                $query->where(function ($q) use ($typeNameColumn, $categoryNameColumn) {
                    $q->whereHas('category', fn($cq) => $cq->where($categoryNameColumn, 'like', '%' . $this->activeCategory . '%'))
                      ->orWhereHas('type', fn($tq) => $tq->where($typeNameColumn, 'like', '%' . $this->activeCategory . '%'));
                });
            }

            $productNameColumn = app()->getLocale() === 'en' ? 'name_en' : 'name_id';

            // Filter search
            if ($this->search !== '') {
                $query->where(function ($q) use ($productNameColumn) {
                    $q->where($productNameColumn, 'like', '%' . $this->search . '%')
                        ->orWhere($productNameColumn === 'name_en' ? 'name_id' : 'name_en', 'like', '%' . $this->search . '%');
                });
            }

            // Sort
            switch ($this->sortBy) {
                case __('messages.name_az'):
                case 'A - Z':
                    $query->orderBy($productNameColumn, 'asc');
                    break;
                case __('messages.name_za'):
                case 'Z - A':
                    $query->orderBy($productNameColumn, 'desc');
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
                    'id'     => $product->product_id,
                    'name'   => $product->name,
                    'cat'    => $product->type->name ?? $product->category->name ?? 'Produk',
                    'img'    => $this->getProductImage($product),
                    'slug'   => $product->getSlug(),
                    'status' => $product->status,
                ];
            })->toArray();
        });
    }

    private function getProductImage($product)
    {
        return $product->getFirstImageUrl();
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
