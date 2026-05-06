<?php

namespace App\Livewire\Admin\Frontend;

use App\Models\Machine;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class MachineList extends Component
{
    use WithPagination, WithFileUploads;

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
    public string  $title         = '';
    public         $image         = null;
    public ?string $existingImage = null;

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
        $machine             = Machine::findOrFail($id);
        $this->editingId     = $id;
        $this->title         = $machine->title;
        $this->existingImage = $machine->image_url;
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
        $this->title         = '';
        $this->image         = null;
        $this->existingImage = null;
        $this->editingId     = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'image' => $this->isEditing
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']
                : ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Judul mesin wajib diisi.',
            'title.max'      => 'Judul maksimal 200 karakter.',
            'image.image'    => 'File harus berupa gambar.',
            'image.mimes'    => 'Format: jpg, jpeg, png, webp.',
            'image.max'      => 'Ukuran gambar maksimal 4MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $imagePath = $this->existingImage;

        if ($this->image) {
            // Hapus gambar lama jika ada
            if ($this->isEditing && $this->existingImage) {
                $oldFilename = basename($this->existingImage);
                $oldPath = 'machine_picture/' . $oldFilename;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Simpan gambar baru ke folder machine_picture
            $filename = uniqid() . '.' . $this->image->getClientOriginalExtension();
            $this->image->storeAs('machine_picture', $filename, 'public');
            
            // Simpan hanya nama file (bukan path lengkap) agar konsisten dengan data lama
            $imagePath = $filename;
        }

        if ($this->isEditing) {
            Machine::findOrFail($this->editingId)->update([
                'title'     => $this->title,
                'image_url' => $imagePath,
            ]);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Data mesin berhasil diperbarui.']);
        } else {
            Machine::create([
                'title'     => $this->title,
                'image_url' => $imagePath,
            ]);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Data mesin berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        $machine = Machine::findOrFail($id);

        if ($machine->image_url) {
            $filename = basename($machine->image_url);
            $path = 'machine_picture/' . $filename;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $machine->delete();

        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'Data mesin berhasil dihapus.']);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $machines = Machine::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.frontend.machine-list', [
            'machines' => $machines,
        ]);
    }
}
