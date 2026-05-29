<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;

class ProductSection extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="py-14 md:py-20 px-4 bg-[#FFF2E0] relative overflow-hidden animate-pulse">
            <div class="max-w-7xl mx-auto">
                <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6">
                    <div>
                        <div class="h-3 w-32 bg-[#FF9100]/20 rounded mb-3 animate-pulse"></div>
                        <div class="h-8 w-64 bg-[#2C1A0E]/20 rounded animate-pulse"></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5 animate-pulse"></div>
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5 animate-pulse"></div>
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5 animate-pulse"></div>
                    <div class="bg-white/90 rounded-3xl p-3 h-[200px] sm:h-[280px] border border-black/5 animate-pulse"></div>
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
