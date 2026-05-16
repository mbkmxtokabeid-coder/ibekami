<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ProductList extends Component
{
    use WithPagination, WithFileUploads;

    // ── Table state ──────────────────────────────────────────────
    public int    $perPage   = 10;
    public string $search    = '';
    public string $sortField = 'created_at';
    public string $sortDir   = 'desc';

    // ── Modal state ──────────────────────────────────────────────
    public bool   $showModal = false;
    public bool   $isEditing = false;
    public ?string $editingId = null;   // UUID

    // ── Form fields ──────────────────────────────────────────────
    public string $name           = '';
    public string $description    = '';
    public string $product_type   = '';
    public string $category_type  = '';
    public string $status         = '';
    public array  $details        = [];
    public array  $images         = [];
    public array  $existingImages = [];

    // ── Filtered categories ───────────────────────────────────────
    public array $filteredCategories = [];

    // ── Watchers ─────────────────────────────────────────────────
    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

    public function updatedProductType(string $value): void
    {
        $this->category_type      = '';
        $this->filteredCategories = $value
            ? Category::where('type_id', $value)->orderBy('name')->get(['id', 'name'])->toArray()
            : [];
    }

    public function sort(string $field): void
    {
        $this->sortDir   = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
        $this->resetPage();
    }

    // ── Detail rows ───────────────────────────────────────────────
    public function addDetail(): void    { $this->details[] = ['key' => '', 'value' => '']; }
    public function removeDetail(int $i): void
    {
        array_splice($this->details, $i, 1);
        $this->details = array_values($this->details);
    }

    public function removeExistingImage(int $i): void
    {
        array_splice($this->existingImages, $i, 1);
        $this->existingImages = array_values($this->existingImages);
    }

    // ── Modal helpers ─────────────────────────────────────────────
    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(string $id): void
    {
        $product = Product::findOrFail($id);

        $this->editingId      = $id;
        $this->name           = $product->name;
        $this->description    = $product->description ?? '';
        $this->product_type   = (string) ($product->product_type ?? '');
        $this->category_type  = (string) ($product->category_type ?? '');
        $this->status         = $product->status ?? 'Tidak Aktif';
        $this->existingImages = $product->image_url ?? [];
        $this->images         = [];
        $this->details        = collect($product->detail ?? [])->map(
            fn ($v, $k) => ['key' => $k, 'value' => $v]
        )->values()->toArray();

        $this->filteredCategories = $this->product_type
            ? Category::where('type_id', $this->product_type)->orderBy('name')->get(['id', 'name'])->toArray()
            : [];

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->name               = '';
        $this->description        = '';
        $this->product_type       = '';
        $this->category_type      = '';
        $this->status             = '';
        $this->details            = [];
        $this->images             = [];
        $this->existingImages     = [];
        $this->filteredCategories = [];
        $this->editingId          = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'product_type'    => ['required', 'exists:types,id'],
            'category_type'   => ['required', 'exists:categories,id'],
            'name'            => ['required', 'string', 'max:200'],
            'description'     => ['nullable', 'string'],
            'status'          => ['required', 'in:Aktif,Tidak Aktif'],
            'details.*.key'   => ['nullable', 'string', 'max:100'],
            'details.*.value' => ['nullable', 'string', 'max:255'],
            'images.*'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'product_type.required'  => 'Jenis produk wajib dipilih.',
            'category_type.required' => 'Kategori produk wajib dipilih.',
            'name.required'          => 'Nama produk wajib diisi.',
            'status.required'        => 'Status produk wajib dipilih.',
            'images.*.image'         => 'File harus berupa gambar.',
            'images.*.mimes'         => 'Format: jpg, jpeg, png, webp.',
            'images.*.max'           => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        // Build detail map
        $detailMap = [];
        foreach ($this->details as $row) {
            if (!empty($row['key'])) {
                $detailMap[$row['key']] = $row['value'] ?? '';
            }
        }

        // Handle images — store only filename (matching old DB format)
        $imagePaths = $this->existingImages;
        foreach ($this->images as $img) {
            $path         = $img->store('products', 'public');
            $imagePaths[] = basename($path); // store filename only like old DB
        }

        $data = [
            'name'          => $this->name,
            'description'   => $this->description ?: null,
            'product_type'  => $this->product_type,
            'category_type' => $this->category_type,
            'status'        => $this->status,
            'detail'        => $detailMap ?: null,
            'image_url'     => $imagePaths ?: null,
        ];

        if ($this->isEditing) {
            $product = Product::findOrFail($this->editingId);
            $product->update($data);
            $this->clearProductCache($product->product_id, $product->name);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Produk berhasil diperbarui.']);
        } else {
            Product::create($data);
            $this->clearAllKatalogCache();
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Produk berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    public function delete(string $id): void
    {
        $product = Product::findOrFail($id);

        // Delete uploaded images (skip old-format filenames without path)
        foreach ($product->image_url ?? [] as $filename) {
            $path = 'products/' . $filename;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $this->clearProductCache($product->product_id, $product->name);
        $product->delete();
        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'Produk berhasil dihapus.']);
    }

    // ── Cache Helpers ─────────────────────────────────────────────

    /**
     * Hapus cache untuk produk tertentu (detail + related) dan semua cache katalog.
     */
    private function clearProductCache(string $productId, string $productName): void
    {
        // Cache detail produk (semua locale)
        foreach (['id', 'en'] as $locale) {
            $slug = \Illuminate\Support\Str::slug($productName);
            cache()->forget("product_detail_{$slug}_{$locale}");
            cache()->forget("related_products_{$productId}_{$locale}");
        }

        // Cache katalog (semua kombinasi filter)
        $this->clearAllKatalogCache();
    }

    /**
     * Hapus semua cache katalog.
     * Cache key katalog menggunakan md5 dari kombinasi filter.
     * Scan file cache dan hapus yang mengandung prefix 'katalog_products_'.
     */
    private function clearAllKatalogCache(): void
    {
        // Hapus cache key default yang paling umum diakses
        foreach (['id', 'en'] as $locale) {
            foreach (['Semua Produk', 'All Products'] as $cat) {
                foreach (['Terbaru', 'Newest', 'A - Z', 'Z - A', 'Terlama', 'Oldest'] as $sort) {
                    $key = 'katalog_products_' . md5($cat . $sort . '' . '' . '' . '' . $locale);
                    cache()->forget($key);
                }
            }
        }

        // Scan file cache untuk hapus semua key katalog (md5 hash tidak bisa di-enumerate)
        $cacheDir = storage_path('framework/cache/data');
        if (!is_dir($cacheDir)) return;

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cacheDir, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (!$file->isFile()) continue;
                $content = @file_get_contents($file->getRealPath());
                if ($content && str_contains($content, 'katalog_products_')) {
                    @unlink($file->getRealPath());
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Cache clear failed: ' . $e->getMessage());
        }
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $products = Product::with(['type', 'category'])
            ->when($this->search, fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhereHas('type', fn ($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('category', fn ($q2) => $q2->where('name', 'like', "%{$this->search}%"))
            )
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        $types = Type::orderBy('name')->get();

        return view('livewire.admin.frontend.product-list', [
            'products' => $products,
            'types'    => $types,
        ]);
    }
}
