<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;

class SosialMedia extends Component
{
    public function placeholder()
    {
        return <<<'HTML'
        <div class="py-16 sm:py-20 px-5 sm:px-6 lg:px-8 bg-[#fdfaf7] animate-pulse">
            <div class="max-w-7xl mx-auto">
                <div class="mb-10 sm:mb-12">
                    <div class="h-3 w-32 bg-[#ff9100]/20 rounded mb-3 animate-pulse"></div>
                    <div class="h-8 w-64 bg-[#2C1A0E]/20 rounded animate-pulse"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <div class="bg-white rounded-3xl p-6 h-28 border border-black/5 animate-pulse"></div>
                    <div class="bg-white rounded-3xl p-6 h-28 border border-black/5 animate-pulse"></div>
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
