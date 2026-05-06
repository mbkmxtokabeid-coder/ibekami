<?php

namespace App\Livewire\Admin\Backend;

use App\Models\Banner;
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
        $banner              = Banner::findOrFail($id);
        $this->editingId     = $id;
        $this->existingMedia = $banner->media_url;
        $this->existingType  = $banner->media_type;
        $this->media         = null;
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
        $this->media         = null;
        $this->existingMedia = null;
        $this->existingType  = null;
        $this->editingId     = null;
        $this->isProcessing  = false;
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
        ];
    }

    protected function messages(): array
    {
        return [
            'media.required' => 'Media wajib diupload.',
            'media.mimes'    => 'Format: jpg, png, webp (gambar) atau mp4, mov, avi, mkv, webm (video).',
            'media.max'      => 'Ukuran file maksimal 100MB.',
        ];
    }

    // ── CRUD ──────────────────────────────────────────────────────
    public function save(): void
    {
        $this->validate();

        $mediaPath = $this->existingMedia;
        $mediaType = $this->existingType ?? 'image';

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
                $mediaPath = $this->media->store('banners', 'public');
            }
        }

        $data = [
            'media_url'  => $mediaPath,
            'media_type' => $mediaType,
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
