<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;

class SosialMedia extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="py-16 sm:py-20 px-5 sm:px-6 lg:px-8 bg-[#fdfaf7]">
            <div class="max-w-7xl mx-auto">
                <div class="mb-10 sm:mb-12">
                    <div class="flex items-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] uppercase tracking-[0.2em] mb-3">
                        {{ __('messages.social_media') }}
                        <span class="w-12 h-[2px] bg-[#b35200]/50 rounded-full"></span>
                    </div>
                    <h2 class="font-['Playfair_Display'] text-3xl sm:text-4xl font-bold text-[#2C1A0E] leading-tight tracking-tight">
                        Follow Us
                    </h2>
                    <p class="text-sm sm:text-base text-[#866b59] mt-3 font-medium leading-relaxed max-w-xl">
                        Ikuti kami untuk update produk terbaru, inspirasi desain, dan promo eksklusif.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 animate-pulse">
                    <div class="bg-white rounded-3xl p-6 h-28 border border-black/5"></div>
                    <div class="bg-white rounded-3xl p-6 h-28 border border-black/5"></div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.halaman-utama.sosial-media');
    }
}
