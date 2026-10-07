<?php

namespace App\Livewire\Katalog;

use Livewire\Component;
use App\Models\Type;
use App\Models\Category;

class MobileFilterBar extends Component
{
    public array $allTypes = [];
    public array $allCategories = [];
    public array $selectedTypes = [];
    public array $selectedCategories = [];
    public string $activeCategory = '';
    public string $search = '';

    protected $listeners = [
        'categoryChanged'    => 'onCategoryChanged',
        'multiFilterChanged' => 'onMultiFilterChanged',
        'filtersReset'       => 'onFiltersReset',
        'searchChanged'      => 'onSearchChanged',
    ];

    public function mount()
    {
        $this->activeCategory = __('messages.all_products');
        if (request()->has('search')) {
            $this->search = (string) request()->get('search');
        }

        if (request()->has('category')) {
            $catSlug = (string) request()->get('category');
            $category = Category::all()->first(function ($c) use ($catSlug) {
                return \Illuminate\Support\Str::slug($c->name_id ?: '') === $catSlug
                    || \Illuminate\Support\Str::slug($c->name_en ?: '') === $catSlug
                    || \Illuminate\Support\Str::slug($c->name) === $catSlug
                    || $c->name === $catSlug;
            });

            if ($category) {
                $this->activeCategory = $category->name;
                $this->selectedCategories = [$category->name];
                $this->selectedTypes = [];
            }
        } elseif (request()->has('type')) {
            $typeSlug = (string) request()->get('type');
            $type = Type::all()->first(function ($t) use ($typeSlug) {
                return \Illuminate\Support\Str::slug($t->name_id ?: '') === $typeSlug
                    || \Illuminate\Support\Str::slug($t->name_en ?: '') === $typeSlug
                    || \Illuminate\Support\Str::slug($t->name) === $typeSlug;
            });

            if ($type) {
                $this->activeCategory = $type->name;
                $this->selectedTypes = [$type->name];
                $this->selectedCategories = [];
            }
        }

        if (request()->has('type') || request()->has('category')) {
            session(['katalog_last_url' => request()->fullUrl()]);
        }

        $this->loadData();
    }

    public function loadData()
    {
        $this->allTypes = [];
        $this->allCategories = [];

        $nameColumn = app()->getLocale() === 'en' ? 'name_en' : 'name_id';

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
            }
        }

        $dbCategories = Category::withCount('products')
            ->orderBy($nameColumn, 'asc')
            ->get();
        foreach ($dbCategories as $cat) {
            if ($cat->products_count > 0) {
                $this->allCategories[] = [
                    'id'      => $cat->id,
                    'type_id' => $cat->type_id,
                    'name'    => $cat->name,
                    'count'   => $cat->products_count,
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
            $this->dispatch('multiFilterChanged', types: [], categories: []);
            $this->dispatch('categoryChanged', category: $cat);
            $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
        } else {
            $type = Type::all()->first(fn($t) => $t->name === $cat || $t->name_id === $cat || $t->name_en === $cat);
            if ($type) {
                $this->selectedTypes = [$type->name];
                $this->selectedCategories = [];
                $slug = \Illuminate\Support\Str::slug($type->name_id ?: $type->name_en ?: $type->name);
                $url = route('katalog', ['type' => $slug]);
                session(['katalog_last_url' => $url]);
                $this->dispatch('multiFilterChanged', types: [$type->name], categories: []);
                $this->dispatch('categoryChanged', category: $type->name);
                $this->js("window.history.replaceState({}, '', '" . $url . "')");
            } else {
                $category = Category::all()->first(fn($c) => $c->name === $cat || $c->name_id === $cat || $c->name_en === $cat);
                if ($category) {
                    $this->selectedTypes = [];
                    $this->selectedCategories = [$category->name];
                    $slug = \Illuminate\Support\Str::slug($category->name_id ?: $category->name_en ?: $category->name);
                    $url = route('katalog', ['category' => $slug]);
                    session(['katalog_last_url' => $url]);
                    $this->dispatch('multiFilterChanged', types: [], categories: [$category->name]);
                    $this->dispatch('categoryChanged', category: $category->name);
                    $this->js("window.history.replaceState({}, '', '" . $url . "')");
                } else {
                    $this->selectedTypes = [];
                    $this->selectedCategories = [];
                    session()->forget('katalog_last_url');
                    $this->dispatch('multiFilterChanged', types: [], categories: []);
                    $this->dispatch('categoryChanged', category: $cat);
                    $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
                }
            }
        }
    }

    public function applyMultiFilter(array $types, array $categories): void
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

        if (count($types) === 1 && count($categories) === 0) {
            $type = Type::all()->first(fn($t) => $t->name === $types[0] || $t->name_id === $types[0] || $t->name_en === $types[0]);
            if ($type) {
                $slug = \Illuminate\Support\Str::slug($type->name_id ?: $type->name_en ?: $type->name);
                $url = route('katalog', ['type' => $slug]);
                session(['katalog_last_url' => $url]);
                $this->js("window.history.replaceState({}, '', '" . $url . "')");
            }
        } elseif (count($categories) === 1 && count($types) === 0) {
            $category = Category::all()->first(fn($c) => $c->name === $categories[0] || $c->name_id === $categories[0] || $c->name_en === $categories[0]);
            if ($category) {
                $slug = \Illuminate\Support\Str::slug($category->name_id ?: $category->name_en ?: $category->name);
                $url = route('katalog', ['category' => $slug]);
                session(['katalog_last_url' => $url]);
                $this->js("window.history.replaceState({}, '', '" . $url . "')");
            }
        } elseif (count($types) === 0 && count($categories) === 0) {
            session()->forget('katalog_last_url');
            $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
        }

        $this->dispatch('multiFilterChanged', types: $types, categories: $categories);
    }

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

    public function updatedSearch(): void
    {
        $this->dispatch('searchChanged', search: $this->search);
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->dispatch('searchChanged', search: '');
    }

    public function onSearchChanged(string $search): void
    {
        $this->search = $search;
    }

    public function onFiltersReset(): void
    {
        $this->activeCategory = __('messages.all_products');
        $this->selectedTypes = [];
        $this->selectedCategories = [];
        $this->search = '';
    }

    public function render()
    {
        return view('livewire.katalog.mobile-filter-bar');
    }
}
