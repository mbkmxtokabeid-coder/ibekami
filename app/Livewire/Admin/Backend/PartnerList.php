<?php

namespace App\Livewire\Admin\Backend;

use App\Models\Partnership;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PartnerList extends Component
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
    public string  $category      = '';
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
        $partner             = Partnership::findOrFail($id);
        $this->editingId     = $id;
        $this->category      = $partner->category;
        $this->existingImage = $partner->image_url;
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
        $this->category      = '';
        $this->image         = null;
        $this->existingImage = null;
        $this->editingId     = null;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'category' => ['required', 'in:BUMN,Organization'],
            'image'    => $this->isEditing
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048']
                : ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ];
    }

    protected function messages(): array
    {
        return [
            'category.required' => 'Jenis partner wajib dipilih.',
            'category.in'       => 'Jenis partner tidak valid.',
            'image.required'    => 'Logo partner wajib diupload.',
            'image.image'       => 'File harus berupa gambar.',
            'image.mimes'       => 'Format: jpg, jpeg, png, webp, svg.',
            'image.max'         => 'Ukuran logo maksimal 2MB.',
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
                $oldPath = 'gambar_partner/' . $oldFilename;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Check if SVG (SVG tidak perlu kompresi)
            $extension = $this->image->getClientOriginalExtension();
            
            if (strtolower($extension) === 'svg') {
                // SVG: simpan langsung tanpa kompresi
                $filename = uniqid('partner_', true) . '.svg';
                $this->image->storeAs('gambar_partner', $filename, 'public');
                $imagePath = $filename;
            } else {
                // Compress image to WebP (100-300KB)
                try {
                    $compressor = new ImageCompressor();
                    $filename = uniqid('partner_', true) . '.webp';
                    $storagePath = 'gambar_partner/' . $filename;
                    
                    $compressor->compressToWebP($this->image->getRealPath(), $storagePath);
                    $imagePath = $filename;
                } catch (\Exception $e) {
                    // Fallback: simpan original
                    $filename = uniqid('partner_', true) . '.' . $extension;
                    $this->image->storeAs('gambar_partner', $filename, 'public');
                    $imagePath = $filename;
                    
                    \Illuminate\Support\Facades\Log::error('Partner image compression failed: ' . $e->getMessage());
                }
            }
        }

        if ($this->isEditing) {
            Partnership::findOrFail($this->editingId)->update([
                'category'  => $this->category,
                'image_url' => $imagePath,
            ]);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Data partner berhasil diperbarui.']);
        } else {
            Partnership::create([
                'category'  => $this->category,
                'image_url' => $imagePath,
            ]);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Partner berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        $partner = Partnership::findOrFail($id);

        if ($partner->image_url) {
            $filename = basename($partner->image_url);
            $path = 'gambar_partner/' . $filename;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $partner->delete();

        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'Data partner berhasil dihapus.']);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $partners = Partnership::query()
            ->when($this->search, fn ($q) =>
                $q->where('category', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortField, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.backend.partner-list', [
            'partners' => $partners,
        ]);
    }
}
