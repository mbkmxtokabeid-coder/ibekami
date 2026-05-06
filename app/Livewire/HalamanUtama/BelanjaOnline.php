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
        if (\Storage::disk('public')->exists('logos/tokopedia.png')) {
            $this->tokopediaLogo = asset('storage/logos/tokopedia.png');
        } else {
            $this->tokopediaLogo = 'https://ibekami.id/storage/logos/tokopedia.png';
        }

        if (\Storage::disk('public')->exists('logos/shopee.png')) {
            $this->shopeeLogo = asset('storage/logos/shopee.png');
        } else {
            $this->shopeeLogo = 'https://ibekami.id/storage/logos/shopee.png';
        }
    }

    public function render()
    {
        return view('livewire.halaman-utama.belanja-online');
    }
}
