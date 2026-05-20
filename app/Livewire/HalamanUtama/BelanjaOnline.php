<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;

class BelanjaOnline extends Component
{
    public string $tokopediaUrl = 'https://www.tokopedia.com/ibekamiid?utm_campaign=Shop--7704797-240426&utm_source=salinlink&utm_medium=share';
    public string $shopeeUrl = 'https://shopee.co.id/ikhtiar_berkah';
    public string $tokopediaLogo;
    public string $shopeeLogo;

    public function mount()
    {
        // Cek apakah logo ada di local storage
        if (\Storage::disk('public')->exists('logos/tokopedia.webp')) {
            $this->tokopediaLogo = asset('storage/logos/tokopedia.webp');
        } else {
            $this->tokopediaLogo = 'https://ibekami.id/storage/logos/tokopedia.webp';
        }

        if (\Storage::disk('public')->exists('logos/shopee.webp')) {
            $this->shopeeLogo = asset('storage/logos/shopee.webp');
        } else {
            $this->shopeeLogo = 'https://ibekami.id/storage/logos/shopee.webp';
        }
    }

    public function render()
    {
        return view('livewire.halaman-utama.belanja-online');
    }
}
