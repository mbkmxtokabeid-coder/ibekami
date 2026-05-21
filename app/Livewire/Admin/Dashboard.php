<?php

namespace App\Livewire\Admin;

use App\Models\Partnership;
use App\Models\Product;
use App\Models\Type;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    use WithPagination;

    public int    $perPage   = 10;
    public string $search    = '';
    public string $sortField = 'name_id';
    public string $sortDir   = 'asc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function sort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDir   = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $totalProducts   = Product::count();
        $totalPartners   = Partnership::count();

        // Chart data: clicks per product type
        $chartData = Type::withCount([
            'products as total_product_clicks' => fn ($q) => $q->select(\DB::raw('sum(click_count)')),
            'products as total_order_clicks'   => fn ($q) => $q->select(\DB::raw('sum(order_click_count)')),
        ])->get();

        $products = Product::query()
            ->when($this->search, function ($q) {
                $q->where('name_id', 'like', "%{$this->search}%")
                  ->orWhere('name_en', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalPartners' => $totalPartners,
            'chartData'     => $chartData,
            'products'      => $products,
        ]);
    }
}
