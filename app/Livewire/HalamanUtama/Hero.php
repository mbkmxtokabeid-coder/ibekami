<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class Hero extends Component
{
    public $banner;
    public $videoUrl;
    public $posterUrl;

    public function mount()
    {
        // Ambil banner pertama yang bertipe video
        $this->banner = Banner::where('media_type', 'video')->first();
        
        if ($this->banner) {
            // Cek apakah file video ada di local storage
            $localPath = $this->banner->media_url;
            
            if (Storage::disk('public')->exists($localPath)) {
                $this->videoUrl = asset('storage/' . $localPath);
            } else {
                // Fallback ke URL ibekami.id
                $this->videoUrl = 'https://ibekami.id/storage/' . $localPath;
            }
            
            // Poster image (optional, bisa dikosongkan jika tidak ada)
            $this->posterUrl = null;
        }
    }

    public function render()
    {
        return view('livewire.halaman-utama.hero');
    }
}
