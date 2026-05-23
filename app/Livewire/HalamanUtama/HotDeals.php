<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Type;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HotDeals extends Component
{
    public $deals;

    public function mount()
    {
        $this->deals = Cache::remember('homepage:hot_deals', now()->addMinutes(30), function () {
            // Ambil semua Type yang memiliki image_url
            return Type::query()
                ->whereNotNull('image_url')
                ->get()
                ->map(function ($deal) {
                    // Cek apakah file ada di local storage
                    if (Storage::disk('public')->exists($deal->image_url)) {
                        $url = asset('storage/' . $deal->image_url);
                        $parsed = parse_url($url);
                        if (isset($parsed['host']) && in_array($parsed['host'], ['localhost', '127.0.0.1'])) {
                            $deal->image_full_url = ($parsed['path'] ?? '') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
                        } else {
                            $deal->image_full_url = $url;
                        }
                    } else {
                        // Fallback ke ibekami.id
                        $deal->image_full_url = 'https://ibekami.id/storage/' . $deal->image_url;
                    }
                    return $deal;
                });
        });
    }

    public function render()
    {
        return view('livewire.halaman-utama.hot-deals');
    }
}
