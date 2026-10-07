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

        if (request()->has('type')) {
            $typeSlug = (string) request()->get('type');
            $type = Type::all()->first(function ($t) use ($typeSlug) {
                return \Illuminate\Support\Str::slug($t->name_id ?: '') === $typeSlug
                    || \Illuminate\Support\Str::slug($t->name_en ?: '') === $typeSlug
                    || \Illuminate\Support\Str::slug($t->name) === $typeSlug;
            });

            if ($type) {
                $this->activeCategory = $type->name;
                $this->selectedTypes = [$type->name];
            }
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
            }
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
            $this->dispatch('multiFilterChanged', types: [], categories: []);
            $this->dispatch('categoryChanged', category: $cat);
            $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
        } else {
            $this->selectedTypes = [$cat];
            $this->selectedCategories = [];
            $this->dispatch('multiFilterChanged', types: [$cat], categories: []);
            $this->dispatch('categoryChanged', category: $cat);

            $type = Type::all()->first(fn($t) => $t->name === $cat || $t->name_id === $cat || $t->name_en === $cat);
            if ($type) {
                $slug = \Illuminate\Support\Str::slug($type->name_id ?: $type->name_en);
                $this->js("window.history.replaceState({}, '', '" . route('katalog', ['type' => $slug]) . "')");
            } else {
                $this->js("window.history.replaceState({}, '', '" . route('katalog') . "')");
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

        $this->dispatch('multiFilterChanged', types: $types, categories: $categories);
    }

    public function onCategoryChanged(string $category): void
    {
        $this->activeCategory = $category;
        if ($category === __('messages.all_products') || $category === 'Semua Produk' || $category === 'All Products') {
            $this->selectedTypes = [];
            $this->selectedCategories = [];
        } else {
            $this->selectedTypes = [$category];
            $this->selectedCategories = [];
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
