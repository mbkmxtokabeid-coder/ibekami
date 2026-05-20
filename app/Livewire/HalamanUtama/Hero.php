<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Hero extends Component
{
    public $banner;
    public $videoUrl;
    public $imageUrl;
    public $posterUrl;
    public $preloadImageUrl;

    public function mount()
    {
        $this->banner = Cache::remember('homepage:hero_banner', now()->addMinutes(10), function () {
            return Banner::query()
                ->orderByDesc('id')
                ->first();
        });

        if ($this->banner) {
            if ($this->banner->media_type === 'video') {
                $this->videoUrl = $this->resolvePublicUrl($this->banner->media_url);
                $this->posterUrl = $this->resolvePublicUrl($this->banner->thumbnail_url);
                $this->preloadImageUrl = $this->posterUrl;
            } else {
                $this->imageUrl = $this->resolvePublicUrl($this->banner->media_url);
                $this->preloadImageUrl = $this->imageUrl;
            }
        }
    }

    private function resolvePublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return 'https://ibekami.id/storage/' . ltrim($path, '/');
    }

    public function render()
    {
        return view('livewire.halaman-utama.hero');
    }
}
