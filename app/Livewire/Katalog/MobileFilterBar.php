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

    protected $listeners = [
        'categoryChanged'    => 'onCategoryChanged',
        'multiFilterChanged' => 'onMultiFilterChanged',
    ];

    public function mount()
    {
        $this->activeCategory = __('messages.all_products');
        $this->loadData();
    }

    public function loadData()
    {
        $dbTypes = Type::withCount('products')->get();
        foreach ($dbTypes as $type) {
            if ($type->products_count > 0) {
                $this->allTypes[] = [
                    'id'    => $type->id,
                    'name'  => $type->name,
                    'count' => $type->products_count,
                ];
            }
        }

        $dbCategories = Category::withCount('products')->get();
        foreach ($dbCategories as $cat) {
            if ($cat->products_count > 0) {
                $this->allCategories[] = [
                    'id'    => $cat->id,
                    'name'  => $cat->name,
                    'count' => $cat->products_count,
                ];
            }
        }
    }

    public function setCategory(string $cat): void
    {
        $this->activeCategory = $cat;
        $this->dispatch('categoryChanged', category: $cat);
    }

    public function applyMultiFilter(array $types, array $categories): void
    {
        $this->selectedTypes      = $types;
        $this->selectedCategories = $categories;
        $this->dispatch('multiFilterChanged', types: $types, categories: $categories);
    }

    public function onCategoryChanged(string $category): void
    {
        $this->activeCategory = $category;
    }

    public function onMultiFilterChanged(array $types, array $categories): void
    {
        $this->selectedTypes      = $types;
        $this->selectedCategories = $categories;
    }

    public function render()
    {
        return view('livewire.katalog.mobile-filter-bar');
    }
}
