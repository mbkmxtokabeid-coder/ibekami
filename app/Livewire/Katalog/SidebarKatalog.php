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
                $this->selectedTypes = [];
            }
        } elseif (request()->has('type')) {
            $typeSlug = (string) request()->get('type');
            $type = Type::findBySlug($typeSlug);

            if ($type) {
                $this->activeCategory = $type->name;
                $this->selectedTypes = [$type->name];
                $this->selectedCategories = [];
            }
        } else {
            session()->forget('katalog_last_url');
        }

        if (request()->has('type') || request()->has('category')) {
            session(['katalog_last_url' => request()->fullUrl()]);
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

        if ($cat === __('messages.all_products') || $cat === 'Semua Produk' || $cat === 'All Products') {
            $this->selectedTypes = [];
            $this->selectedCategories = [];
            session()->forget('katalog_last_url');
            $this->dispatch('categoryChanged', category: $cat);
            $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
        } else {
            $type = Type::all()->first(fn($t) => $t->name === $cat || $t->name_id === $cat || $t->name_en === $cat);
            if ($type) {
                $this->selectedTypes = [$type->name];
                $this->selectedCategories = [];
                $slug = $type->getSlug();
                $url = route('katalog', ['type' => $slug]);
                session(['katalog_last_url' => $url]);
                $this->dispatch('categoryChanged', category: $type->name);
                $this->js("window.history.replaceState({}, '', '" . $url . "')");
            } else {
                $category = Category::all()->first(fn($c) => $c->name === $cat || $c->name_id === $cat || $c->name_en === $cat);
                if ($category) {
                    $this->selectedTypes = [];
                    $this->selectedCategories = [$category->name];
                    $slug = Str::slug($category->name_id ?: $category->name_en ?: $category->name);
                    $url = route('katalog', ['category' => $slug]);
                    session(['katalog_last_url' => $url]);
                    $this->dispatch('categoryChanged', category: $category->name);
                    $this->js("window.history.replaceState({}, '', '" . $url . "')");
                } else {
                    $this->selectedTypes = [];
                    $this->selectedCategories = [];
                    session()->forget('katalog_last_url');
                    $this->dispatch('categoryChanged', category: $cat);
                    $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
                }
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
        if ($category === __('messages.all_products') || $category === 'Semua Produk' || $category === 'All Products') {
            $this->selectedTypes = [];
            $this->selectedCategories = [];
        } else {
            $type = Type::all()->first(fn($t) => $t->name === $category || $t->name_id === $category || $t->name_en === $category);
            if ($type) {
                $this->selectedTypes = [$type->name];
                $this->selectedCategories = [];
            } else {
                $cat = Category::all()->first(fn($c) => $c->name === $category || $c->name_id === $category || $c->name_en === $category);
                if ($cat) {
                    $this->selectedTypes = [];
                    $this->selectedCategories = [$cat->name];
                }
            }
        }
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

        if (count($types) === 1 && count($categories) === 0) {
            $type = Type::all()->first(fn($t) => $t->name === $types[0] || $t->name_id === $types[0] || $t->name_en === $types[0]);
            if ($type) {
                $slug = $type->getSlug();
                $url = route('katalog', ['type' => $slug]);
                session(['katalog_last_url' => $url]);
                $this->js("window.history.replaceState({}, '', '" . $url . "')");
                return;
            }
        } elseif (count($categories) === 1 && count($types) === 0) {
            $category = Category::all()->first(fn($c) => $c->name === $categories[0] || $c->name_id === $categories[0] || $c->name_en === $categories[0]);
            if ($category) {
                $slug = Str::slug($category->name_id ?: $category->name_en ?: $category->name);
                $url = route('katalog', ['category' => $slug]);
                session(['katalog_last_url' => $url]);
                $this->js("window.history.replaceState({}, '', '" . $url . "')");
                return;
            }
        } elseif (count($types) === 0 && count($categories) === 0) {
            session()->forget('katalog_last_url');
            $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
            return;
        }
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

        session()->forget('katalog_last_url');

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
