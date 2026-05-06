<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Type;
use Illuminate\Support\Facades\Storage;

class HotDeals extends Component
{
    public $deals;

    public function mount()
    {
        // Ambil semua Type yang memiliki image_url
        $this->deals = Type::whereNotNull('image_url')->get()->map(function($deal) {
            // Cek apakah file ada di local storage
            if (Storage::disk('public')->exists($deal->image_url)) {
                $deal->image_full_url = asset('storage/' . $deal->image_url);
            } else {
                // Fallback ke ibekami.id
                $deal->image_full_url = 'https://ibekami.id/storage/' . $deal->image_url;
            }
            return $deal;
        });
    }

    public function render()
    {
        return view('livewire.halaman-utama.hot-deals');
    }
}
