<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Promo;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PromoProduct extends Component
{
    use WithFileUploads, WithPagination;

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

    public $image = null;

    public ?string $existingImage = null;

    // ── Listeners ────────────────────────────────────────────────
    protected $listeners = ['confirmDelete' => 'delete'];

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
        $promo = Promo::findOrFail($id);
        $this->editingId = $id;
        $this->name_id = $promo->name_id ?? '';
        $this->name_en = $promo->name_en ?? '';
        $this->existingImage = $promo->image_url;
        $this->image = null;
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
        $this->image = null;
        $this->existingImage = null;
        $this->editingId = null;
        $this->resetValidation();
    }

    // ── Validation rules ──────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name_id' => ['required', 'string', 'max:100'],
            'name_en' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name_id.required' => 'Nama promo produk (Bahasa Indonesia) wajib diisi.',
            'name_id.max' => 'Nama Indonesia maksimal 100 karakter.',
            'name_en.required' => 'Nama promo produk (English) wajib diisi.',
            'name_en.max' => 'Nama English maksimal 100 karakter.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar: jpg, jpeg, png, webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $imagePath = $this->existingImage;

        if ($this->image) {
            if ($this->isEditing && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('promo', 'public');
        }

        $data = [
            'name_id' => $this->name_id,
            'name_en' => $this->name_en,
            'image_url' => $imagePath,
        ];

        if ($this->isEditing) {
            Promo::findOrFail($this->editingId)->update($data);
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Promo produk berhasil diperbarui.',
            ]);
        } else {
            Promo::create($data);
            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Promo produk berhasil ditambahkan.',
            ]);
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->delete($id);
    }

    public function delete(int $id): void
    {
        $promo = Promo::findOrFail($id);

        if ($promo->image_url) {
            Storage::disk('public')->delete($promo->image_url);
        }

        $promo->delete();

        $this->dispatch('swal', [
            'type' => 'success',
            'title' => 'Dihapus!',
            'text' => 'Promo produk berhasil dihapus.',
        ]);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $promos = Promo::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name_id', 'like', "%{$this->search}%")
                        ->orWhere('name_en', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.frontend.product-promo', [
            'promos' => $promos,
        ]);
    }
}
