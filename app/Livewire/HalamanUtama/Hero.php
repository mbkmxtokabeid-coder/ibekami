<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Hero extends Component
{
    public $banners = [];
    public $preloadImageUrl;

    public function mount()
    {
        $rawBanners = Cache::remember('homepage:hero_banner', now()->addMinutes(10), function () {
            return Banner::query()
                ->orderBy('id', 'asc')
                ->take(4)
                ->get();
        });

        $this->banners = $rawBanners->map(function ($banner) {
            return [
                'id'  => $banner->id,
                'url' => $this->resolvePublicUrl($banner->media_url),
            ];
        });

        if ($this->banners->isNotEmpty()) {
            $this->preloadImageUrl = $this->banners->first()['url'];
        }
    }

    private function resolvePublicUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Storage::disk('public')->exists($path)) {
            $url = asset('storage/' . $path);
            $parsed = parse_url($url);
            if (isset($parsed['host']) && in_array($parsed['host'], ['localhost', '127.0.0.1'])) {
                return ($parsed['path'] ?? '') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
            }
            return $url;
        }

        return 'https://ibekami.id/storage/' . ltrim($path, '/');
    }

    public function render()
    {
        return view('livewire.halaman-utama.hero');
    }
}
