<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Category;
use App\Models\Type;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ProductCategory extends Component
{
    use WithPagination;

    // ── Table state ──────────────────────────────────────────────
    public int    $perPage   = 10;
    public string $search    = '';
    public string $sortField = 'id';
    public string $sortDir   = 'asc';

    // ── Modal state ──────────────────────────────────────────────
    public bool  $showModal = false;
    public bool  $isEditing = false;
    public ?int  $editingId = null;

    // ── Form fields ──────────────────────────────────────────────
    public string $name    = '';
    public string $type_id = '';

    // ── Watchers ─────────────────────────────────────────────────
    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingPerPage(): void { $this->resetPage(); }

    public function sort(string $field): void
    {
        $this->sortDir   = ($this->sortField === $field && $this->sortDir === 'asc') ? 'desc' : 'asc';
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
        $cat             = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name      = $cat->name;
        $this->type_id   = (string) $cat->type_id;
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
        $this->name      = '';
        $this->type_id   = '';
        $this->editingId = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'type_id' => ['required', 'exists:types,id'],
            'name'    => ['required', 'string', 'max:150'],
        ];
    }

    protected function messages(): array
    {
        return [
            'type_id.required' => 'Jenis produk wajib dipilih.',
            'type_id.exists'   => 'Jenis produk tidak valid.',
            'name.required'    => 'Nama kategori wajib diisi.',
            'name.max'         => 'Nama kategori maksimal 150 karakter.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            Category::findOrFail($this->editingId)->update([
                'type_id' => $this->type_id,
                'name'    => $this->name,
            ]);
            $this->dispatch('swal', [
                'type'  => 'success',
                'title' => 'Berhasil!',
                'text'  => 'Kategori produk berhasil diperbarui.',
            ]);
        } else {
            Category::create([
                'type_id' => $this->type_id,
                'name'    => $this->name,
            ]);
            $this->dispatch('swal', [
                'type'  => 'success',
                'title' => 'Berhasil!',
                'text'  => 'Kategori produk berhasil ditambahkan.',
            ]);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();

        $this->dispatch('swal', [
            'type'  => 'success',
            'title' => 'Dihapus!',
            'text'  => 'Kategori produk berhasil dihapus.',
        ]);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $categories = Category::with('type')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhereHas('type', fn ($q2) => $q2->where('name', 'like', "%{$this->search}%"));
            })
            ->when(
                in_array($this->sortField, ['id', 'name']),
                fn ($q) => $q->orderBy($this->sortField, $this->sortDir),
                fn ($q) => $q->join('types', 'categories.type_id', '=', 'types.id')
                             ->orderBy('types.name', $this->sortDir)
                             ->select('categories.*')
            )
            ->paginate($this->perPage);

        $types = Type::orderBy('name')->get();

        return view('livewire.admin.frontend.product-category', [
            'categories' => $categories,
            'types'      => $types,
        ]);
    }
}
