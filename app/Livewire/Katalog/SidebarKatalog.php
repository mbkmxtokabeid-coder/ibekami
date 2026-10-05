<?php

namespace App\Livewire\Katalog;

use Livewire\Component;
use App\Models\Category;
use App\Models\Type;
use App\Models\Product;

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

    public function mount()
    {
        $this->activeCategory = __('messages.all_products');
        $this->sortBy = __('messages.newest');
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

        // Load all categories grouped by type_id
        $dbCategories = Category::withCount('products')->get();
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
                    'id'    => $cat->id,
                    'name'  => $cat->name,
                    'count' => $cat->products_count,
                ];
            }
        }

        // Build 2-level structure: Type with nested categories
        $dbTypes = Type::withCount('products')->get();
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
        $this->dispatch('categoryChanged', category: $cat);
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

    /** Dipanggil dari popup filter — terapkan multi-select */
    public function applyMultiFilter(array $types, array $categories): void
    {
        $this->selectedTypes     = $types;
        $this->selectedCategories = $categories;

        $this->dispatch('multiFilterChanged', types: $types, categories: $categories);
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
    }

    public function render()
    {
        return view('livewire.katalog.sidebar-katalog');
    }
}
