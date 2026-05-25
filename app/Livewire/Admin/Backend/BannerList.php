<?php

namespace App\Livewire\Admin\Backend;

use App\Models\Banner;
use App\Services\ImageCompressor;
use App\Services\VideoCompressor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BannerList extends Component
{
    use WithPagination, WithFileUploads;

    const MAX_BANNERS = 4;

    // ── Table state ──────────────────────────────────────────────
    public int    $perPage   = 10;
    public string $search    = '';
    public string $sortField = 'id';
    public string $sortDir   = 'asc';

    // ── Modal state ──────────────────────────────────────────────
    public bool  $showModal    = false;
    public bool  $isEditing    = false;
    public ?int  $editingId    = null;
    public bool  $isProcessing = false;

    // ── Form fields ──────────────────────────────────────────────
    public        $media         = null;
    public ?string $existingMedia = null;
    public ?string $existingType  = null;

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
        if (Banner::count() >= self::MAX_BANNERS) {
            $this->dispatch('swal', [
                'type'  => 'warning',
                'title' => 'Batas Tercapai',
                'text'  => 'Maksimal ' . self::MAX_BANNERS . ' banner. Edit atau hapus banner yang ada.',
            ]);
            return;
        }
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $banner                = Banner::findOrFail($id);
        $this->editingId       = $id;
        $this->existingMedia   = $banner->media_url;
        $this->existingType    = $banner->media_type;
        $this->media           = null;
        $this->isEditing       = true;
        $this->showModal       = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->media              = null;
        $this->existingMedia      = null;
        $this->existingType       = null;
        $this->editingId          = null;
        $this->isProcessing       = false;
        $this->resetValidation();
    }

    // ── Validation ────────────────────────────────────────────────
    protected function rules(): array
    {
        $mediaRequired = $this->isEditing ? 'nullable' : 'required';

        return [
            'media' => [
                $mediaRequired,
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2 MB max for image
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'media.required'     => 'Gambar wajib diupload.',
            'media.mimes'        => 'Format gambar: jpg, jpeg, png, atau webp.',
            'media.max'          => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $mediaPath = $this->existingMedia;
        $mediaType = 'image';

        if ($this->media) {
            // Delete old file
            if ($this->isEditing && $this->existingMedia) {
                Storage::disk('public')->delete($this->existingMedia);
            }

            // Compress image to WebP (20-50KB)
            $this->isProcessing = true;
            $filename = Str::uuid() . '.webp';
            $outputPath = 'banners/' . $filename;

            try {
                $compressor = new ImageCompressor();
                $tempPath   = $this->media->getRealPath();
                $mediaPath  = $compressor->compressToWebP($tempPath, $outputPath);
            } catch (\Throwable $e) {
                // Fallback: store original
                $mediaPath = $this->media->store('banners', 'public');
                $this->dispatch('swal', [
                    'type'  => 'warning',
                    'title' => 'Perhatian',
                    'text'  => 'Kompresi gagal. Gambar disimpan tanpa kompresi: ' . $e->getMessage(),
                ]);
            }
            $this->isProcessing = false;
        }

        $data = [
            'media_url'     => $mediaPath,
            'media_type'    => $mediaType,
            'thumbnail_url' => null,
        ];

        if ($this->isEditing) {
            Banner::findOrFail($this->editingId)->update($data);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Banner berhasil diperbarui.']);
        } else {
            Banner::create($data);
            $this->dispatch('swal', ['type' => 'success', 'title' => 'Berhasil!', 'text' => 'Banner berhasil ditambahkan.']);
        }

        $this->closeModal();
    }

    public function delete(int $id): void
    {
        $banner = Banner::findOrFail($id);

        if ($banner->media_url) {
            Storage::disk('public')->delete($banner->media_url);
        }

        if ($banner->thumbnail_url) {
            Storage::disk('public')->delete($banner->thumbnail_url);
        }

        $banner->delete();

        $this->dispatch('swal', ['type' => 'success', 'title' => 'Dihapus!', 'text' => 'Banner berhasil dihapus.']);
    }

    // ── Render ────────────────────────────────────────────────────
    public function render()
    {
        $banners      = Banner::when($this->search, function ($query) {
                            $query->where('media_url', 'like', '%' . $this->search . '%')
                                  ->orWhere('media_type', 'like', '%' . $this->search . '%');
                        })
                        ->orderBy($this->sortField, $this->sortDir)
                        ->paginate($this->perPage);
        $bannerCount  = Banner::count();
        $maxReached   = $bannerCount >= self::MAX_BANNERS;

        return view('livewire.admin.backend.banner-list', [
            'banners'    => $banners,
            'maxReached' => $maxReached,
        ]);
    }
}
