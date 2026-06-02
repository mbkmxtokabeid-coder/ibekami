<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Type;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HotDeals extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div id="hot-deals" class="py-16 md:py-24 px-4 bg-[#fdfaf7] relative overflow-hidden animate-pulse">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 mb-10 lg:mb-16">
                    <div class="space-y-4">
                        <div class="h-6 w-36 bg-[#ff9100]/20 rounded-full animate-pulse"></div>
                        <div class="h-10 w-64 bg-[#2C1A0E]/20 rounded animate-pulse"></div>
                        <div class="h-4 w-96 bg-[#8A6A54]/20 rounded animate-pulse"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 md:gap-6">
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 animate-pulse"></div>
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 animate-pulse"></div>
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 animate-pulse"></div>
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 animate-pulse"></div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        $deals = Cache::remember('homepage:hot_deals', now()->addMinutes(30), function () {
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

        return view('livewire.halaman-utama.hot-deals', [
            'deals' => $deals
        ]);
    }
}
