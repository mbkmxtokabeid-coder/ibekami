<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\Type;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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
    public ?string $editingId = null;

    // ── Form fields ──────────────────────────────────────────────
    public string $name_id          = '';
    public string $name_en          = '';
    public string $description_id   = '';
    public string $description_en   = '';
    public string $product_type     = '';
    public string $category_type    = '';
    public string $status           = '';
    public array  $details_id       = [];
    public array  $details_en       = [];
    public array  $images           = [];
    public array  $existingImages   = [];

    public array $filteredCategories = [];

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

    public function updatedProductType(string $value): void
    {
        $this->category_type      = '';
        $this->filteredCategories = $value
            ? Category::where('type_id', $value)->orderBy('name_id')->get()
                ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray()
            : [];
    }

    public function sort(string $field): void
    {
        $this->sortDir   = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
        $this->resetPage();
    }

    public function addDetailId(): void
    {
        $this->details_id[] = ['key' => '', 'value' => ''];
    }

    public function addDetailEn(): void
    {
        $this->details_en[] = ['key' => '', 'value' => ''];
    }

    public function removeDetailId(int $i): void
    {
        array_splice($this->details_id, $i, 1);
        $this->details_id = array_values($this->details_id);
    }

    public function removeDetailEn(int $i): void
    {
        array_splice($this->details_en, $i, 1);
        $this->details_en = array_values($this->details_en);
    }

    public function removeExistingImage(int $i): void
    {
        array_splice($this->existingImages, $i, 1);
        $this->existingImages = array_values($this->existingImages);
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(string $id): void
    {
        $product = Product::findOrFail($id);

        $this->editingId        = $id;
        $this->name_id          = $this->loadTextField($product, 'name_id');
        $this->name_en          = $this->loadTextField($product, 'name_en');
        $this->description_id   = $this->loadTextField($product, 'description_id');
        $this->description_en   = $this->loadTextField($product, 'description_en');
        $this->product_type     = (string) ($product->product_type ?? '');
        $this->category_type    = (string) ($product->category_type ?? '');
        $this->status           = $product->status ?? 'Tidak Aktif';
        $this->existingImages   = $product->image_url ?? [];
        $this->images           = [];
        $this->details_id       = $this->loadDetailForForm($product, 'detail_id');
        $this->details_en       = $this->loadDetailForForm($product, 'detail_en');

        $this->filteredCategories = $this->product_type
            ? Category::where('type_id', $this->product_type)->orderBy('name_id')->get()
                ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray()
            : [];

        $this->isEditing = true;
        $this->showModal  = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function loadTextField(Product $product, string $column): string
    {
        $attributes = $product->getAttributes();

        if (array_key_exists($column, $attributes) && $attributes[$column] !== null && $attributes[$column] !== '') {
            return (string) $attributes[$column];
        }

        $raw = $product->getRawOriginal($column);

        if (is_string($raw) && $raw !== '') {
            return $raw;
        }

        return '';
    }

    private function resetForm(): void
    {
        $this->name_id              = '';
        $this->name_en              = '';
        $this->description_id       = '';
        $this->description_en       = '';
        $this->product_type         = '';
        $this->category_type        = '';
        $this->status               = '';
        $this->details_id           = [];
        $this->details_en           = [];
        $this->images               = [];
        $this->existingImages       = [];
        $this->filteredCategories   = [];
        $this->editingId            = null;
        $this->resetValidation();
    }

    protected function rules(): array
    {
        return [
            'product_type'      => ['required', 'exists:types,id'],
            'category_type'     => ['required', 'exists:categories,id'],
            'name_id'           => ['required', 'string', 'max:200'],
            'name_en'           => ['required', 'string', 'max:200'],
            'description_id'    => ['nullable', 'string'],
            'description_en'    => ['nullable', 'string'],
            'status'            => ['required', 'in:Aktif,Tidak Aktif'],
            'details_id.*.key'  => ['nullable', 'string', 'max:100'],
            'details_id.*.value'=> ['nullable', 'string', 'max:255'],
            'details_en.*.key'  => ['nullable', 'string', 'max:100'],
            'details_en.*.value'=> ['nullable', 'string', 'max:255'],
            'images.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'product_type.required'  => 'Jenis produk wajib dipilih.',
            'category_type.required' => 'Kategori produk wajib dipilih.',
            'name_id.required'       => 'Nama produk (Bahasa Indonesia) wajib diisi.',
            'name_en.required'       => 'Nama produk (English) wajib diisi.',
            'status.required'        => 'Status produk wajib dipilih.',
            'images.*.image'         => 'File harus berupa gambar.',
            'images.*.mimes'         => 'Format: jpg, jpeg, png, webp.',
            'images.*.max'           => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function save(): void
    {
        try {
            $this->validate();
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? 'Periksa kembali data form.';
            $this->dispatch('swal', [
                'type'  => 'error',
                'title' => 'Validasi gagal',
                'text'  => $message,
            ]);
            throw $e;
        }

        // 1. Get or generate the Product ID
        if ($this->isEditing) {
            $productId = $this->editingId;
            $product = Product::findOrFail($productId);
        } else {
            $productId = (string) \Illuminate\Support\Str::uuid();
        }

        // 2. Slugify the product name (using name_id or falling back to name_en)
        $slug = \Illuminate\Support\Str::slug($this->name_id ?: $this->name_en ?: 'product');

        // 3. Process existing images (rename them to match the new SEO slug structure if needed)
        $imagePaths = [];
        if ($this->isEditing) {
            foreach ($this->existingImages as $i => $oldFilename) {
                // Determine target SEO filename for this image index
                $newFilename = $this->getUniqueSeoFilename($slug, $i, $productId, $oldFilename);
                
                if ($oldFilename !== $newFilename) {
                    $oldPath = 'products/' . $oldFilename;
                    $newPath = 'products/' . $newFilename;
                    
                    try {
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->move($oldPath, $newPath);
                        }
                        // Clear search/resolve cache for both filenames
                        \Illuminate\Support\Facades\Cache::forget('prod_img_url:' . md5($oldFilename));
                        \Illuminate\Support\Facades\Cache::forget('prod_img_url:' . md5($newFilename));
                        $imagePaths[] = $newFilename;
                    } catch (\Exception $moveEx) {
                        \Illuminate\Support\Facades\Log::error('Failed to move image from ' . $oldPath . ' to ' . $newPath . ': ' . $moveEx->getMessage());
                        // Fallback to the old name if rename fails
                        $imagePaths[] = $oldFilename;
                    }
                } else {
                    $imagePaths[] = $oldFilename;
                }
            }
        }

        // 4. Process new uploaded images
        foreach ($this->images as $img) {
            $newImageIndex = count($imagePaths);
            $filename = $this->getUniqueSeoFilename($slug, $newImageIndex, $productId);
            $storagePath = 'products/' . $filename;
            
            try {
                if (ImageCompressor::isAvailable()) {
                    $compressor = new ImageCompressor();
                    $compressor->compressToWebP($img->getRealPath(), $storagePath);
                    $imagePaths[] = $filename;
                } else {
                    $path = $img->storeAs('products', $filename, 'public');
                    $imagePaths[] = basename($path);
                    \Illuminate\Support\Facades\Log::warning('Image saved without compression: GD extension not available');
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Image processing failed: ' . $e->getMessage());
                try {
                    $path = $img->storeAs('products', $filename, 'public');
                    $imagePaths[] = basename($path);
                } catch (\Exception $e2) {
                    $this->dispatch('swal', [
                        'type'  => 'error',
                        'title' => 'Gagal!',
                        'text'  => 'Gagal menyimpan gambar: ' . $e2->getMessage(),
                    ]);
                    return;
                }
            }
        }

        $data = [
            'name_id'         => $this->name_id,
            'name_en'         => $this->name_en,
            'description_id'  => $this->description_id ?: null,
            'description_en'  => $this->description_en ?: null,
            'product_type'    => $this->product_type,
            'category_type'   => $this->category_type,
            'status'          => $this->status,
            'detail_id'       => $this->buildDetailMap($this->details_id),
            'detail_en'       => $this->buildDetailMap($this->details_en),
            'image_url'       => $imagePaths ?: null,
        ];

        if ($this->isEditing) {
            $oldSlug = $product->getSlug();
            if ($product->status !== 'Aktif' && $this->status === 'Aktif') {
                $data['activated_at'] = now();
            }
            $product->update($data);
            $this->clearProductCache($product->product_id, $oldSlug, $product->getSlug());
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Produk berhasil diperbarui.']);
        } else {
            $data['product_id'] = $productId;
            if ($this->status === 'Aktif') {
                $data['activated_at'] = now();
            }
            Product::create($data);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Produk berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    private function generateSeoFilename(string $slug, int $index, string $productId): string
    {
        $patterns = [
            "cetak-{$slug}-terdekat-di-medan",
            "cetak-{$slug}-satuan-di-medan",
            "cetak-{$slug}-express-di-medan",
            "cetak-{$slug}-grosiran-di-medan",
            "cetak-{$slug}-partai-besar-di-medan",
            "cetak-{$slug}-partai-kecil-di-medan",
            "cetak-{$slug}-custom-di-medan",
            "tempat-cetak-{$slug}-di-medan",
            "jasa-cetak-{$slug}-di-medan",
            "percetakan-{$slug}-di-medan"
        ];

        $totalPatterns = count($patterns);
        $startIndex = abs(crc32($productId)) % $totalPatterns;
        $patternIndex = ($startIndex + $index) % $totalPatterns;
        
        return $patterns[$patternIndex];
    }

    private function getUniqueSeoFilename(string $slug, int $index, string $productId, ?string $currentFilename = null): string
    {
        $baseName = $this->generateSeoFilename($slug, $index, $productId);
        $filename = $baseName . '.webp';
        
        // If the filename matches the current filename of this image, keep it
        if ($currentFilename && $currentFilename === $filename) {
            return $filename;
        }
        
        // Loop until we find a name that doesn't exist in products/ directory
        $counter = 1;
        while (Storage::disk('public')->exists('products/' . $filename)) {
            $filename = $baseName . '-' . $counter . '.webp';
            
            // If the generated filename with counter matches the current filename, keep it
            if ($currentFilename && $currentFilename === $filename) {
                return $filename;
            }
            $counter++;
        }
        
        return $filename;
    }


    public function delete(string $id): void
    {
        $product = Product::findOrFail($id);

        foreach ($product->image_url ?? [] as $filename) {
            $path = 'products/' . $filename;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $this->clearProductCache($product->product_id, $product->getSlug(), $product->getSlug());
        $product->delete();
        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'Produk berhasil dihapus.']);
    }

    /**
     * Ambil detail dari DB (termasuk fallback raw JSON) lalu ubah ke baris form.
     */
    private function loadDetailForForm(Product $product, string $column): array
    {
        $detail = $product->getAttributeValue($column);

        if (empty($detail)) {
            $raw = $product->getRawOriginal($column);
            if (is_string($raw) && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    $detail = $decoded;
                }
            }
        }

        return $this->normalizeDetailForForm($detail);
    }

    /**
     * Dukung format map {"Ukuran":"10cm"} atau list [{"key":"Size","value":"10cm"}].
     */
    private function normalizeDetailForForm(mixed $detail): array
    {
        if ($detail === null || $detail === '') {
            return [];
        }

        if (is_string($detail)) {
            $detail = json_decode($detail, true);
        }

        if (! is_array($detail) || $detail === []) {
            return [];
        }

        // Format list dari form lama / JSON array
        if (array_is_list($detail)) {
            $first = $detail[0] ?? null;
            if (is_array($first) && array_key_exists('key', $first)) {
                return array_values(array_map(static fn (array $row): array => [
                    'key'   => (string) ($row['key'] ?? ''),
                    'value' => (string) ($row['value'] ?? ''),
                ], $detail));
            }
        }

        // Format map key => value
        $rows = [];
        foreach ($detail as $key => $value) {
            if ($key === '' && $value === '') {
                continue;
            }
            if (is_array($value)) {
                continue;
            }
            $rows[] = [
                'key'   => (string) $key,
                'value' => is_scalar($value) ? (string) $value : '',
            ];
        }

        return $rows;
    }

    private function buildDetailMap(array $rows): ?array
    {
        $map = [];
        foreach ($rows as $row) {
            if (!empty($row['key'])) {
                $map[$row['key']] = $row['value'] ?? '';
            }
        }

        return $map ?: null;
    }

    private function clearProductCache(string $productId, string $oldSlug, string $newSlug): void
    {
        foreach (['id', 'en'] as $locale) {
            cache()->forget("product_detail_{$oldSlug}_{$locale}");
            cache()->forget("product_detail_{$newSlug}_{$locale}");
            cache()->forget("related_products_{$productId}_{$locale}");
        }
    }

    public function render()
    {
        $products = Product::with(['type', 'category'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name_id', 'like', "%{$this->search}%")
                        ->orWhere('name_en', 'like', "%{$this->search}%")
                        ->orWhere('description_id', 'like', "%{$this->search}%")
                        ->orWhere('description_en', 'like', "%{$this->search}%");
                })
                ->orWhereHas('type', function ($q2) {
                    $q2->where('name_id', 'like', "%{$this->search}%")
                       ->orWhere('name_en', 'like', "%{$this->search}%");
                })
                ->orWhereHas('category', function ($q2) {
                    $q2->where('name_id', 'like', "%{$this->search}%")
                       ->orWhere('name_en', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        $types = Type::orderBy('name_id')->get();

        return view('livewire.admin.frontend.product-list', [
            'products' => $products,
            'types'    => $types,
        ]);
    }
}
