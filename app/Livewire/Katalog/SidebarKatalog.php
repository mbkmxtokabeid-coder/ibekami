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

    public function mount()
    {
        // Set default values using translation
        $this->activeCategory = __('messages.all_products');
        $this->sortBy = __('messages.newest');
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = [];
        
        // Hitung total semua produk (tanpa filter status)
        $totalProducts = Product::count();
        $this->categories[] = ['name' => __('messages.all_products'), 'count' => $totalProducts];

        // Ambil categories dengan count (semua produk)
        $dbCategories = Category::withCount('products')->get();

        foreach ($dbCategories as $cat) {
            if ($cat->products_count > 0) {
                $this->categories[] = [
                    'name' => $cat->name,
                    'count' => $cat->products_count
                ];
            }
        }

        // Ambil types dengan count (semua produk)
        $dbTypes = Type::withCount('products')->get();

        foreach ($dbTypes as $type) {
            if ($type->products_count > 0) {
                $this->categories[] = [
                    'name' => $type->name,
                    'count' => $type->products_count
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

    public function render()
    {
        return view('livewire.katalog.sidebar-katalog');
    }
}
