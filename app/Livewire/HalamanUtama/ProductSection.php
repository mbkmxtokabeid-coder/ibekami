<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;

class ProductSection extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="py-14 md:py-20 px-4 bg-[#FFF2E0] relative overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 text-[10px] font-semibold text-[#FF9100] uppercase tracking-[0.2em] mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FF9100] animate-pulse"></span>
                            {{ __('messages.our_collection') }}
                        </div>
                        <h2 class="text-2xl md:text-4xl font-extrabold text-[#2C1A0E] leading-tight">
                            {{ __('messages.available_products') }}
                        </h2>
                        <p class="text-[12px] md:text-sm text-[#8A6A54] mt-2 max-w-md leading-relaxed">
                            {{ __('messages.made_with_love') }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5 animate-pulse">
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5"></div>
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5"></div>
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5"></div>
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5"></div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.halaman-utama.product-section');
    }
}
