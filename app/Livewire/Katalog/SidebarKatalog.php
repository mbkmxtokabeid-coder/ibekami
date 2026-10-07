<?php

namespace App\Livewire\Katalog;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;
use Illuminate\Support\Str;

class SidebarKatalog extends Component
{
    public string $search = '';
    public string $activeCategory = '';
    public string $sortBy = '';
    public array $categories = [];

    // Struktur 2 tingkat: Type → Categories
    public array $typesWithCategories = [];

    // Multi-select filter state
    public array $selectedTypes = [];
    public array $selectedCategories = [];

    // Data untuk popup filter
    public array $allTypes = [];
    public array $allCategories = [];

    protected $listeners = [
        'categoryChanged'    => 'onCategoryChanged',
        'multiFilterChanged' => 'onMultiFilterChanged',
        'filtersReset'       => 'onFiltersReset',
        'searchChanged'      => 'onSearchChanged',
    ];

    public function mount()
    {
        $this->activeCategory = __('messages.all_products');
        $this->sortBy = __('messages.newest');

        if (request()->has('search')) {
            $this->search = (string) request()->get('search');
        }

        if (request()->has('type')) {
            $typeSlug = (string) request()->get('type');
            $type = Type::all()->first(function ($t) use ($typeSlug) {
                return Str::slug($t->name_id ?: '') === $typeSlug
                    || Str::slug($t->name_en ?: '') === $typeSlug
                    || Str::slug($t->name) === $typeSlug;
            });

            if ($type) {
                $this->activeCategory = $type->name;
                $this->selectedTypes = [$type->name];
            }
        }

        if (request()->has('category')) {
            $catSlug = (string) request()->get('category');
            $category = Category::all()->first(function ($c) use ($catSlug) {
                return Str::slug($c->name_id ?: '') === $catSlug
                    || Str::slug($c->name_en ?: '') === $catSlug
                    || Str::slug($c->name) === $catSlug
                    || $c->name === $catSlug;
            });

            if ($category) {
                $this->activeCategory = $category->name;
                $this->selectedCategories = [$category->name];
            }
        }

        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = [];
        $this->allTypes = [];
        $this->allCategories = [];
        $this->typesWithCategories = [];

        $totalProducts = Product::count();
        $this->categories[] = ['name' => __('messages.all_products'), 'count' => $totalProducts, 'group' => 'all'];

        $nameColumn = app()->getLocale() === 'en' ? 'name_en' : 'name_id';

        // Load all categories grouped by type_id, diurutkan A-Z
        $dbCategories = Category::withCount('products')
            ->orderBy($nameColumn, 'asc')
            ->get();
        $categoriesByType = [];
        foreach ($dbCategories as $cat) {
            if ($cat->products_count > 0) {
                $categoriesByType[$cat->type_id][] = [
                    'id'      => $cat->id,
                    'name'    => $cat->name,
                    'count'   => $cat->products_count,
                    'type_id' => $cat->type_id,
                ];
                $this->allCategories[] = [
                    'id'      => $cat->id,
                    'type_id' => $cat->type_id,
                    'name'    => $cat->name,
                    'count'   => $cat->products_count,
                ];
            }
        }

        // Build 2-level structure: Type with nested categories, diurutkan A-Z
        $dbTypes = Type::withCount('products')
            ->orderBy($nameColumn, 'asc')
            ->get();
        foreach ($dbTypes as $type) {
            if ($type->products_count > 0) {
                $this->allTypes[] = [
                    'id'    => $type->id,
                    'name'  => $type->name,
                    'count' => $type->products_count,
                ];

                $this->typesWithCategories[] = [
                    'id'         => $type->id,
                    'name'       => $type->name,
                    'count'      => $type->products_count,
                    'categories' => $categoriesByType[$type->id] ?? [],
                ];
            }
        }
    }

    public function setCategory(string $cat): void
    {
        $this->activeCategory = $cat;
        $this->selectedTypes = [];
        $this->selectedCategories = [];
        $this->dispatch('categoryChanged', category: $cat);

        // Update URL browser tanpa reload halaman
        if ($cat === __('messages.all_products') || $cat === 'Semua Produk' || $cat === 'All Products') {
            $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
        } else {
            $type = Type::all()->first(fn($t) => $t->name === $cat || $t->name_id === $cat || $t->name_en === $cat);
            if ($type) {
                $slug = Str::slug($type->name_id ?: $type->name_en);
                $this->js("window.history.replaceState({}, '', '" . route('katalog', ['type' => $slug]) . "')");
            } else {
                $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
            }
        }
    }

    public function setSort(string $sort): void
    {
        $this->sortBy = $sort;
        $this->dispatch('sortChanged', sort: $sort);
    }

    public function updatedSearch(): void
    {
        $this->dispatch('searchChanged', search: $this->search);
    }

    #[On('categoryChanged')]
    public function onCategoryChanged(string $category): void
    {
        $this->activeCategory = $category;
    }

    #[On('searchChanged')]
    public function onSearchChanged(string $search): void
    {
        $this->search = $search;
    }

    /** Dipanggil dari popup filter — terapkan multi-select */
    #[On('multiFilterChanged')]
    public function onMultiFilterChanged(array $types, array $categories): void
    {
        $this->selectedTypes      = $types;
        $this->selectedCategories = $categories;

        if (count($types) > 0) {
            $this->activeCategory = $types[0];
        } elseif (count($categories) > 0) {
            $this->activeCategory = $categories[0];
        } else {
            $this->activeCategory = __('messages.all_products');
        }
    }

    public function applyMultiFilter(array $types, array $categories): void
    {
        $this->onMultiFilterChanged($types, $categories);
        $this->dispatch('multiFilterChanged', types: $types, categories: $categories);
    }

    #[On('filtersReset')]
    public function onFiltersReset(): void
    {
        $this->resetAllFilters();
    }

    /** Reset semua filter termasuk multi-select */
    public function resetAllFilters(): void
    {
        $this->selectedTypes      = [];
        $this->selectedCategories = [];
        $this->activeCategory     = __('messages.all_products');
        $this->sortBy             = __('messages.newest');
        $this->search             = '';

        $this->dispatch('multiFilterChanged', types: [], categories: []);
        $this->dispatch('categoryChanged', category: $this->activeCategory);
        $this->dispatch('sortChanged', sort: $this->sortBy);
        $this->dispatch('searchChanged', search: '');
        $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
    }

    public function render()
    {
        return view('livewire.katalog.sidebar-katalog');
    }
}
