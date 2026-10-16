<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Promo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HotDeals extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <section id="hot-deals" class="py-10 md:py-14 px-4 bg-[#fdfaf7] dark:bg-[#1A120B] relative overflow-hidden">
            <div class="max-w-7xl mx-auto relative z-10">
                <div class="mb-8 sm:mb-10 lg:mb-12 relative flex flex-col items-center justify-center">
                    <div class="text-center max-w-4xl mx-auto px-2">
                        <div class="flex items-center justify-center gap-2 sm:gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] dark:text-[#ff9100] uppercase tracking-[0.15em] sm:tracking-[0.2em] mb-2 sm:mb-3">
                            <span class="w-8 sm:w-12 h-[1px] bg-[#b35200] dark:bg-[#ff9100] shrink-0"></span>
                            <span class="whitespace-nowrap">{{ __('messages.special_offer') }}</span>
                            <span class="w-8 sm:w-12 h-[1px] bg-[#b35200] dark:bg-[#ff9100] shrink-0"></span>
                        </div>
                        <h2 class="font-['Poppins',sans-serif] text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#2C1A0E] dark:text-[#FDF5EC] tracking-tight leading-tight">
                            {{ __('messages.hot_deals_this_month') }}
                        </h2>
                        <p class="text-[12px] sm:text-[13px] md:text-sm text-[#886852] dark:text-[#9E8B7D] mt-2 leading-relaxed max-w-4xl mx-auto sm:whitespace-nowrap">
                            {{ __('messages.best_price_all_categories') }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 md:gap-6 animate-pulse">
                    <div class="bg-white/80 dark:bg-[#231811] rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 dark:border-white/10"></div>
                    <div class="bg-white/80 dark:bg-[#231811] rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 dark:border-white/10"></div>
                    <div class="bg-white/80 dark:bg-[#231811] rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 dark:border-white/10"></div>
                    <div class="bg-white/80 dark:bg-[#231811] rounded-3xl p-2.5 h-[280px] sm:h-[380px] border border-black/5 dark:border-white/10"></div>
                </div>
            </div>
        </section>
        HTML;
    }

    public function render()
    {
        $locale = app()->getLocale();
        $deals = Cache::remember('homepage:hot_deals_' . $locale, now()->addMinutes(30), function () {
            // Ambil semua Type yang memiliki image_url
            return Promo::query()
                ->whereNotNull('image_url')
                ->get()
                ->map(function ($deal) {
                    $path = $deal->image_url;
                    if (!Storage::disk('public')->exists($path)) {
                        if (Storage::disk('public')->exists('gambar_promo/' . $path)) {
                            $path = 'gambar_promo/' . $path;
                        } elseif (Storage::disk('public')->exists('promo/' . $path)) {
                            $path = 'promo/' . $path;
                        }
                    }
                    if (Storage::disk('public')->exists($path)) {
                        $url = asset('storage/' . $path);
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
