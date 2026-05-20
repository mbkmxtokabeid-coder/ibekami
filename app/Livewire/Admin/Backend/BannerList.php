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

    const MAX_BANNERS = 1;

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
    public        $thumbnail     = null;
    public ?string $existingMedia = null;
    public ?string $existingType  = null;
    public ?string $existingThumbnail = null;

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
        $this->existingThumbnail = $banner->thumbnail_url;
        $this->media           = null;
        $this->thumbnail       = null;
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
        $this->thumbnail          = null;
        $this->existingMedia      = null;
        $this->existingType       = null;
        $this->existingThumbnail  = null;
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
                'mimes:jpg,jpeg,png,webp,mp4,mov,avi,mkv,webm',
                'max:102400', // 100 MB max upload
            ],
            'thumbnail' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // 2 MB max for thumbnail
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'media.required'     => 'Media wajib diupload.',
            'media.mimes'        => 'Format: jpg, png, webp (gambar) atau mp4, mov, avi, mkv, webm (video).',
            'media.max'          => 'Ukuran file maksimal 100MB.',
            'thumbnail.mimes'    => 'Format thumbnail: jpg, png, atau webp.',
            'thumbnail.max'      => 'Ukuran thumbnail maksimal 2MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $mediaPath = $this->existingMedia;
        $mediaType = $this->existingType ?? 'image';
        $thumbnailPath = $this->existingThumbnail;

        if ($this->media) {
            // Delete old file
            if ($this->isEditing && $this->existingMedia) {
                Storage::disk('public')->delete($this->existingMedia);
            }

            $mime      = $this->media->getMimeType();
            $isVideo   = str_starts_with($mime, 'video/');
            $mediaType = $isVideo ? 'video' : 'image';

            if ($isVideo) {
                // Convert & compress to WebM
                $this->isProcessing = true;
                $outputPath = 'banners/' . Str::uuid() . '.webm';

                try {
                    $compressor = new VideoCompressor();
                    $tempPath   = $this->media->getRealPath();
                    $mediaPath  = $compressor->compressToWebM($tempPath, $outputPath);
                } catch (\Throwable $e) {
                    // FFmpeg not available — store original as fallback
                    $mediaPath = $this->media->store('banners', 'public');
                    $this->dispatch('swal', [
                        'type'  => 'warning',
                        'title' => 'Perhatian',
                        'text'  => 'FFmpeg tidak tersedia. Video disimpan tanpa kompresi. Install FFmpeg untuk kompresi otomatis.',
                    ]);
                }
                $this->isProcessing = false;
            } else {
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
        }

        // Handle thumbnail upload
        if ($this->thumbnail) {
            // Delete old thumbnail
            if ($this->isEditing && $this->existingThumbnail) {
                Storage::disk('public')->delete($this->existingThumbnail);
            }

            // Compress thumbnail to WebP (<100KB)
            $this->isProcessing = true;
            $filename = 'thumb_' . Str::uuid() . '.webp';
            $outputPath = 'banners/' . $filename;

            try {
                $compressor = new ImageCompressor();
                $tempPath   = $this->thumbnail->getRealPath();
                
                // Use lower target for thumbnails (20-100KB)
                $thumbnailPath = $compressor->compressToWebP($tempPath, $outputPath, 100 * 1024);
            } catch (\Throwable $e) {
                // Fallback: store original
                $thumbnailPath = $this->thumbnail->store('banners', 'public');
                $this->dispatch('swal', [
                    'type'  => 'warning',
                    'title' => 'Perhatian',
                    'text'  => 'Kompresi thumbnail gagal. Gambar disimpan tanpa kompresi: ' . $e->getMessage(),
                ]);
            }
            $this->isProcessing = false;
        }

        $data = [
            'media_url'     => $mediaPath,
            'media_type'    => $mediaType,
            'thumbnail_url' => $thumbnailPath,
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
        $banners      = Banner::orderBy($this->sortField, $this->sortDir)->paginate($this->perPage);
        $bannerCount  = Banner::count();
        $maxReached   = $bannerCount >= self::MAX_BANNERS;

        return view('livewire.admin.backend.banner-list', [
            'banners'    => $banners,
            'maxReached' => $maxReached,
        ]);
    }
}
