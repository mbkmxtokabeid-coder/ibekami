<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Category;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ProductCategory extends Component
{
    use WithPagination;

    // ── Table state ──────────────────────────────────────────────
    public int $perPage = 10;

    public string $search = '';

    public string $sortField = 'id';

    public string $sortDir = 'asc';

    // ── Modal state ──────────────────────────────────────────────
    public bool $showModal = false;

    public bool $isEditing = false;

    public ?int $editingId = null;

    // ── Form fields ──────────────────────────────────────────────
    public string $name_id = '';

    public string $name_en = '';

    public string $type_id = '';

    public string $new_type_name_id = '';

    public string $new_type_name_en = '';

    // ── Delete Category modal state ─────────────────────────────
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public string $deletingName = '';
    public int $deletingProductCount = 0;
    /** 'move' = pindahkan produk ke kategori lain, 'cascade' = hapus kategori + produk */
    public string $deleteMode = 'move';
    public string $targetCategoryId = '';

    // ── Delete Type modal state ──────────────────────────────────
    public bool $showDeleteTypeModal = false;
    public string $selectedDeleteTypeId = '';
    public int $deletingTypeCategoryCount = 0;
    public int $deletingTypeProductCount = 0;
    public string $deleteTypeMode = 'move';
    public string $targetTypeId = '';


    // ── Watchers ─────────────────────────────────────────────────
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
        $this->sortDir = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
        $this->resetPage();
    }

    // ── Modal helpers ─────────────────────────────────────────────
    public function openCreate(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name_id = $cat->name_id ?? '';
        $this->name_en = $cat->name_en ?? '';
        $this->type_id = (string) $cat->type_id;
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
        $this->name_id = '';
        $this->name_en = '';
        $this->type_id = '';

        $this->new_type_name_id = '';
        $this->new_type_name_en = '';

        $this->editingId = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        $rules = [
            'type_id' => ['required'],
            'name_id' => ['required', 'string', 'max:150'],
            'name_en' => ['required', 'string', 'max:150'],
        ];

        if ($this->type_id === 'new') {
            $rules['new_type_name_id'] = ['required', 'string', 'max:150'];
            $rules['new_type_name_en'] = ['required', 'string', 'max:150'];
        } else {
            $rules['type_id'][] = 'exists:types,id';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'type_id.required' => 'Jenis produk wajib dipilih.',
            'type_id.exists' => 'Jenis produk tidak valid.',

            'new_type_name_id.required' => 'Nama jenis produk Bahasa Indonesia wajib diisi.',
            'new_type_name_id.max' => 'Nama jenis produk Bahasa Indonesia maksimal 150 karakter.',

            'new_type_name_en.required' => 'Nama jenis produk English wajib diisi.',
            'new_type_name_en.max' => 'Nama jenis produk English maksimal 150 karakter.',

            'name_id.required' => 'Nama kategori (Bahasa Indonesia) wajib diisi.',
            'name_id.max' => 'Nama Indonesia maksimal 150 karakter.',

            'name_en.required' => 'Nama kategori (English) wajib diisi.',
            'name_en.max' => 'Nama English maksimal 150 karakter.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        if ($this->type_id === 'new') {

            // Buat jenis produk baru
            $type = Type::create([
                'name_id' => $this->new_type_name_id,
                'name_en' => $this->new_type_name_en,
            ]);

            // Gunakan ID jenis yang baru dibuat
            $typeId = $type->id;

        } else {
            // Gunakan jenis produk yang sudah ada
            $typeId = $this->type_id;
        }

        $data = [
            'type_id' => $typeId,
            'name_id' => $this->name_id,
            'name_en' => $this->name_en,
        ];

        if ($this->isEditing) {

            Category::findOrFail($this->editingId)->update($data);

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Kategori produk berhasil diperbarui.',
            ]);

        } else {

            Category::create($data);

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => $this->type_id === 'new'
                    ? 'Jenis produk dan kategori berhasil ditambahkan.'
                    : 'Kategori produk berhasil ditambahkan.',
            ]);
        }

        $this->closeModal();
    }

    // ── Delete ────────────────────────────────────────────────────
    public function openDelete(int $id): void
    {
        $cat = Category::withCount('products')->findOrFail($id);

        $this->resetValidation();
        $this->deletingId = $cat->id;
        $this->deletingName = $cat->name_id ?? '';
        $this->deletingProductCount = (int) $cat->products_count;
        $this->deleteMode = 'move';
        $this->targetCategoryId = '';
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->resetValidation();
    }

    public function openDeleteType(): void
    {
        $this->resetValidation();
        $this->selectedDeleteTypeId = '';
        $this->deletingTypeCategoryCount = 0;
        $this->deletingTypeProductCount = 0;
        $this->deleteTypeMode = 'move';
        $this->targetTypeId = '';
        $this->showDeleteTypeModal = true;
    }

    public function updatedSelectedDeleteTypeId($value): void
    {
        $this->resetValidation();
        $this->targetTypeId = '';
        $this->deleteTypeMode = 'move';

        if (!empty($value)) {
            $type = Type::withCount(['categories', 'products'])->find($value);
            $this->deletingTypeCategoryCount = (int) ($type?->categories_count ?? 0);
            $this->deletingTypeProductCount = (int) ($type?->products_count ?? 0);
        } else {
            $this->deletingTypeCategoryCount = 0;
            $this->deletingTypeProductCount = 0;
        }
    }

    public function closeDeleteTypeModal(): void
    {
        $this->showDeleteTypeModal = false;
        $this->resetValidation();
    }

    public function confirmDelete(): void
    {
        $category = Category::findOrFail($this->deletingId);
        $productCount = $category->products()->count();

        // Tidak ada produk terkait → langsung hapus kategori
        if ($productCount === 0) {
            $category->delete();
            $this->closeDeleteModal();
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Dihapus!',
                'text' => 'Kategori produk berhasil dihapus.',
            ]);

            return;
        }

        $this->validate([
            'deleteMode' => ['required', 'in:move,cascade'],
            'targetCategoryId' => $this->deleteMode === 'move'
                ? ['required', 'exists:categories,id', 'not_in:'.$category->id]
                : ['nullable'],
        ], [
            'targetCategoryId.required' => 'Pilih kategori pengganti untuk produk.',
            'targetCategoryId.exists' => 'Kategori pengganti tidak valid.',
            'targetCategoryId.not_in' => 'Kategori pengganti tidak boleh sama.',
        ]);

        if ($this->deleteMode === 'move') {
            $target = Category::findOrFail($this->targetCategoryId);

            DB::transaction(function () use ($category, $target) {
                // Pindahkan produk ke kategori baru (jenis produk ikut disesuaikan)
                $category->products()->get()->each(function (Product $product) use ($target) {
                    $product->update([
                        'category_type' => $target->id,
                        'product_type' => $target->type_id,
                    ]);
                    $this->clearProductCache($product);
                });

                $category->delete();
            });

            $text = "Kategori dihapus. {$productCount} produk dipindahkan ke kategori \"{$target->name_id}\".";
        } else {
            $products = $category->products()->get();
            $imagePaths = [];

            DB::transaction(function () use ($category, $products, &$imagePaths) {
                foreach ($products as $product) {
                    foreach ($product->image_url ?? [] as $filename) {
                        $imagePaths[] = 'products/'.$filename;
                    }
                    $this->clearProductCache($product);
                    $product->delete();
                }

                $category->delete();
            });

            // Hapus file gambar setelah transaksi DB berhasil
            foreach ($imagePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $text = "Kategori beserta {$productCount} produk berhasil dihapus.";
        }

        $this->closeDeleteModal();
        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Dihapus!',
            'text' => $text,
        ]);
    }

    public function confirmDeleteType(): void
    {
        $this->validate([
            'selectedDeleteTypeId' => ['required', 'exists:types,id'],
        ], [
            'selectedDeleteTypeId.required' => 'Pilih jenis produk yang ingin dihapus.',
            'selectedDeleteTypeId.exists' => 'Jenis produk tidak valid.',
        ]);

        $type = Type::withCount(['categories', 'products'])->findOrFail($this->selectedDeleteTypeId);
        $catCount = (int) $type->categories_count;
        $prodCount = (int) $type->products_count;

        // Jika tidak ada kategori dan tidak ada produk terkait, langsung hapus jenis
        if ($catCount === 0 && $prodCount === 0) {
            if ($type->image_url && Storage::disk('public')->exists($type->image_url)) {
                Storage::disk('public')->delete($type->image_url);
            }
            $type->delete();
            $this->closeDeleteTypeModal();
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Dihapus!',
                'text' => "Jenis produk \"{$type->name_id}\" berhasil dihapus.",
            ]);

            return;
        }

        $this->validate([
            'deleteTypeMode' => ['required', 'in:move,cascade'],
            'targetTypeId' => $this->deleteTypeMode === 'move'
                ? ['required', 'exists:types,id', 'not_in:'.$type->id]
                : ['nullable'],
        ], [
            'targetTypeId.required' => 'Pilih jenis produk pengganti.',
            'targetTypeId.exists' => 'Jenis produk pengganti tidak valid.',
            'targetTypeId.not_in' => 'Jenis produk pengganti tidak boleh sama.',
        ]);

        if ($this->deleteTypeMode === 'move') {
            $targetType = Type::findOrFail($this->targetTypeId);

            DB::transaction(function () use ($type, $targetType) {
                // Alihkan kategori ke jenis baru
                Category::where('type_id', $type->id)->update(['type_id' => $targetType->id]);

                // Alihkan produk ke jenis baru
                $products = Product::where('product_type', $type->id)->get();
                foreach ($products as $prod) {
                    $prod->update(['product_type' => $targetType->id]);
                    $this->clearProductCache($prod);
                }

                if ($type->image_url && Storage::disk('public')->exists($type->image_url)) {
                    Storage::disk('public')->delete($type->image_url);
                }

                $type->delete();
            });

            $text = "Jenis produk dihapus. {$catCount} kategori dan {$prodCount} produk dialihkan ke jenis \"{$targetType->name_id}\".";
        } else {
            $products = Product::where('product_type', $type->id)->get();
            $imagePaths = [];

            DB::transaction(function () use ($type, $products, &$imagePaths) {
                foreach ($products as $prod) {
                    foreach ($prod->image_url ?? [] as $filename) {
                        $imagePaths[] = 'products/'.$filename;
                    }
                    $this->clearProductCache($prod);
                    $prod->delete();
                }

                Category::where('type_id', $type->id)->delete();

                if ($type->image_url && Storage::disk('public')->exists($type->image_url)) {
                    $imagePaths[] = $type->image_url;
                }

                $type->delete();
            });

            foreach ($imagePaths as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $text = "Jenis produk beserta {$catCount} kategori dan {$prodCount} produk berhasil dihapus.";
        }

        $this->closeDeleteTypeModal();
        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Dihapus!',
            'text' => $text,
        ]);
    }

    private function clearProductCache(Product $product): void
    {
        $slug = $product->getSlug();
        foreach (['id', 'en'] as $locale) {
            cache()->forget("product_detail_{$slug}_{$locale}");
            cache()->forget("related_products_{$product->product_id}_{$locale}");
        }
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $categories = Category::with('type')
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name_id', 'like', "%{$this->search}%")
                        ->orWhere('name_en', 'like', "%{$this->search}%");
                })->orWhereHas('type', function ($q2) {
                    $q2->where('name_id', 'like', "%{$this->search}%")
                        ->orWhere('name_en', 'like', "%{$this->search}%");
                });
            })
            ->when(
                in_array($this->sortField, ['id', 'name_id', 'name_en']),
                fn ($q) => $q->orderBy($this->sortField, $this->sortDir),
                fn ($q) => $q->join('types', 'categories.type_id', '=', 'types.id')
                    ->orderBy('types.name_id', $this->sortDir)
                    ->select('categories.*')
            )
            ->paginate($this->perPage);

        $types = Type::orderBy('name_id')->get();

        // Daftar kategori pengganti (kecuali kategori yang sedang dihapus)
        $replacementCategories = $this->deletingId
            ? Category::with('type')
                ->where('id', '!=', $this->deletingId)
                ->orderBy('name_id')
                ->get()
            : collect();

        // Daftar jenis pengganti (kecuali jenis yang sedang dipilih untuk dihapus)
        $replacementTypes = $this->selectedDeleteTypeId
            ? Type::where('id', '!=', $this->selectedDeleteTypeId)
                ->orderBy('name_id')
                ->get()
            : collect();

        return view('livewire.admin.frontend.product-category', [
            'categories' => $categories,
            'types' => $types,
            'replacementCategories' => $replacementCategories,
            'replacementTypes' => $replacementTypes,
        ]);
    }
}
