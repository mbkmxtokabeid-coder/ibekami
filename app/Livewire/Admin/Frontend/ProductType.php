<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Type;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ProductType extends Component
{
    use WithPagination, WithFileUploads;

    // ── Table state ──────────────────────────────────────────────
    public int    $perPage   = 10;
    public string $search    = '';
    public string $sortField = 'id';
    public string $sortDir   = 'asc';

    // ── Modal state ──────────────────────────────────────────────
    public bool   $showModal  = false;
    public bool   $isEditing  = false;
    public ?int   $editingId  = null;

    // ── Form fields ──────────────────────────────────────────────
    public string $name      = '';
    public $image            = null;   // uploaded file
    public ?string $existingImage = null;

    // ── Listeners ────────────────────────────────────────────────
    protected $listeners = ['confirmDelete' => 'delete'];

    // ── Watchers ─────────────────────────────────────────────────
    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

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
        $type = Type::findOrFail($id);
        $this->editingId     = $id;
        $this->name          = $type->name;
        $this->existingImage = $type->image_url;
        $this->image         = null;
        $this->isEditing     = true;
        $this->showModal     = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->name          = '';
        $this->image         = null;
        $this->existingImage = null;
        $this->editingId     = null;
        $this->resetValidation();
    }

    // ── Validation rules ──────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:100'],
            'image' => $this->isEditing
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
                : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama jenis produk wajib diisi.',
            'name.max'      => 'Nama maksimal 100 karakter.',
            'image.image'   => 'File harus berupa gambar.',
            'image.mimes'   => 'Format gambar: jpg, jpeg, png, webp.',
            'image.max'     => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $imagePath = $this->existingImage;

        if ($this->image) {
            // Delete old image if editing
            if ($this->isEditing && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imagePath = $this->image->store('types', 'public');
        }

        if ($this->isEditing) {
            $type = Type::findOrFail($this->editingId);
            $type->update([
                'name'      => $this->name,
                'image_url' => $imagePath,
            ]);
            $this->dispatch('swal', [
                'type'  => 'success',
                'title' => 'Berhasil!',
                'text'  => 'Jenis produk berhasil diperbarui.',
            ]);
        } else {
            Type::create([
                'name'      => $this->name,
                'image_url' => $imagePath,
            ]);
            $this->dispatch('swal', [
                'type'  => 'success',
                'title' => 'Berhasil!',
                'text'  => 'Jenis produk berhasil ditambahkan.',
            ]);
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        // Triggered from JS SweetAlert confirm
        $this->delete($id);
    }

    public function delete(int $id): void
    {
        $type = Type::findOrFail($id);

        if ($type->image_url) {
            Storage::disk('public')->delete($type->image_url);
        }

        $type->delete();

        $this->dispatch('swal', [
            'type'  => 'success',
            'title' => 'Dihapus!',
            'text'  => 'Jenis produk berhasil dihapus.',
        ]);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $types = Type::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.frontend.product-type', [
            'types' => $types,
        ]);
    }
}
