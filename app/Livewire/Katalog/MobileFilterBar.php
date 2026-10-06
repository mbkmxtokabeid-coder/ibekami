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
        $this->loadData();
    }

    public function loadData()
    {
        $this->allTypes = [];
        $this->allCategories = [];

        $dbTypes = Type::withCount('products')
            ->orderByDesc('products_count')
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
            ->orderByDesc('products_count')
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
        } else {
            $this->selectedTypes = [$cat];
            $this->selectedCategories = [];
            $this->dispatch('multiFilterChanged', types: [$cat], categories: []);
            $this->dispatch('categoryChanged', category: $cat);
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
