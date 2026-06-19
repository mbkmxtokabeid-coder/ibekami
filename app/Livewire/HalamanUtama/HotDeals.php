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
        <div id="hot-deals" class="py-16 md:py-24 px-4 bg-[#fdfaf7] relative overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 mb-10 lg:mb-16">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#4a3728]/10 border border-[#4a3728]/20">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4a3728] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4a3728]"></span>
                            </span>
                            <span class="text-[10px] font-bold text-[#4a3728] uppercase tracking-widest">{{ __('messages.special_offer') }}</span>
                        </div>
                        <h2 class="font-['Playfair_Display'] text-3xl md:text-4xl lg:text-5xl font-black text-[#2C1A0E] tracking-tight">
                            {{ __('messages.hot_deals_this_month') }}
                        </h2>
                        <p class="text-[13px] sm:text-[14px] md:text-[15px] text-[#886852] font-medium max-w-md leading-relaxed">
                            {{ __('messages.best_price_all_categories') }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 md:gap-6 animate-pulse">
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5"></div>
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5"></div>
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5"></div>
                    <div class="bg-white rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5"></div>
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
