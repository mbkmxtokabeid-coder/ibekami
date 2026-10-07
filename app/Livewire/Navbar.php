<?php

namespace App\Livewire;

use App\Models\Type;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public array $productTypes = [];

    public string $search = '';

    public bool $isKatalogPage = false;
    public ?string $selectedTypeSlug = null;
    public ?string $selectedCategory = null;

    protected $listeners = [
        'categoryChanged'    => 'onCategoryChanged',
        'multiFilterChanged' => 'onMultiFilterChanged',
        'filtersReset'       => 'onFiltersReset',
    ];

    public function mount(): void
    {
        $this->loadProductTypes();
        $this->initCatalogState();
    }

    public function initCatalogState(): void
    {
        // Pastikan HANYA aktif jika URL saat ini benar-benar rute katalog
        $this->isKatalogPage = request()->routeIs('katalog') || request()->is('katalog');

        if ($this->isKatalogPage) {
            if (request()->has('category')) {
                $catSlug = (string) request()->get('category');
                $cat = Category::with('type')->get()->first(function ($c) use ($catSlug) {
                    return Str::slug($c->name_id ?: '') === $catSlug
                        || Str::slug($c->name_en ?: '') === $catSlug
                        || Str::slug($c->name) === $catSlug
                        || $c->name === $catSlug;
                });

                if ($cat) {
                    $this->selectedCategory = $cat->name;
                    if ($cat->type) {
                        $this->selectedTypeSlug = Str::slug($cat->type->name_id ?: $cat->type->name_en);
                    }
                }
            } elseif (request()->has('type')) {
                $typeSlug = (string) request()->get('type');
                $type = Type::all()->first(function ($t) use ($typeSlug) {
                    return Str::slug($t->name_id ?: '') === $typeSlug
                        || Str::slug($t->name_en ?: '') === $typeSlug
                        || Str::slug($t->name) === $typeSlug;
                });

                if ($type) {
                    $this->selectedTypeSlug = Str::slug($type->name_id ?: $type->name_en);
                    $this->selectedCategory = $type->name;
                } else {
                    $this->selectedTypeSlug = $typeSlug;
                    $this->selectedCategory = null;
                }
            } else {
                $this->selectedCategory = __('messages.all_products');
                $this->selectedTypeSlug = null;
            }
        } else {
            $this->isKatalogPage = false;
            $this->selectedTypeSlug = null;
            $this->selectedCategory = null;
        }
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

    #[On('categoryChanged')]
    public function onCategoryChanged(string $category): void
    {
        if (!$this->isKatalogPage) {
            return;
        }

        $this->selectedCategory = $category;

        if ($category === __('messages.all_products') || $category === 'Semua Produk' || $category === 'All Products') {
            $this->selectedTypeSlug = null;
        } else {
            $type = Type::all()->first(fn($t) => $t->name === $category || $t->name_id === $category || $t->name_en === $category);
            if ($type) {
                $this->selectedTypeSlug = Str::slug($type->name_id ?: $type->name_en);
            } else {
                $cat = Category::with('type')->get()->first(fn($c) => $c->name === $category || $c->name_id === $category || $c->name_en === $category);
                if ($cat && $cat->type) {
                    $this->selectedTypeSlug = Str::slug($cat->type->name_id ?: $cat->type->name_en);
                } else {
                    $this->selectedTypeSlug = null;
                }
            }
        }
    }

    #[On('filtersReset')]
    public function onFiltersReset(): void
    {
        if (!$this->isKatalogPage) {
            return;
        }

        $this->selectedCategory = __('messages.all_products');
        $this->selectedTypeSlug = null;
    }

    #[On('multiFilterChanged')]
    public function onMultiFilterChanged(array $types = [], array $categories = []): void
    {
        if (!$this->isKatalogPage) {
            return;
        }

        if (count($types) > 0) {
            $this->onCategoryChanged($types[0]);
        } elseif (count($categories) > 0) {
            $this->onCategoryChanged($categories[0]);
        } else {
            $this->selectedCategory = __('messages.all_products');
            $this->selectedTypeSlug = null;
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
