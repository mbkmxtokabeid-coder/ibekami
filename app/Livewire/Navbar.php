<?php

namespace App\Livewire;

use App\Models\Type;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public array $productTypes = [];

    public string $search = '';

    public function mount(): void
    {
        $this->loadProductTypes();
    }

    public function loadProductTypes(): void
    {
        $locale = app()->getLocale();
        $this->productTypes = Cache::remember("navbar:product_types_{$locale}", now()->addMinutes(60), function () {
            return Type::orderBy('name_id', 'asc')
                ->get()
                ->map(function ($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'slug' => Str::slug($type->name_id ?: $type->name_en),
                    ];
                })
                ->toArray();
        });
    }

    public function performSearch()
    {
        if (trim($this->search) !== '') {
            return redirect()->route('katalog', ['search' => $this->search]);
        }
    }

    #[On('changeLocale')]
    public function changeLocale($locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session(['locale' => $locale]);
            app()->setLocale($locale);

            // Reload halaman untuk apply perubahan bahasa
            $this->js('window.location.reload()');
        }
    }

    public function render()
    {
        $this->loadProductTypes();

        return view('livewire.navbar');
    }
}
