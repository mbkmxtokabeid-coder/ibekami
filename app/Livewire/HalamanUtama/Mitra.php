<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Partnership;

class Mitra extends Component
{
    public array $partners = [];
    public array $partnersBumn = [];
    public array $partnersOrganization = [];

    public function mount(): void
    {
        $this->loadPartners();
    }

    public function loadPartners(): void
    {
        // Load all partners from database
        $allPartners = Partnership::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($partner) {
                return [
                    'id' => $partner->id,
                    'category' => $partner->category ?? 'BUMN',
                    'image' => $this->getPartnerImage($partner),
                ];
            })
            ->toArray();

        // Separate by category (case-insensitive) - STRICT MODE
        $this->partnersBumn = array_values(array_filter($allPartners, function ($partner) {
            return strtoupper(trim($partner['category'])) === 'BUMN';
        }));

        $this->partnersOrganization = array_values(array_filter($allPartners, function ($partner) {
            $cat = strtoupper(trim($partner['category']));
            return $cat === 'ORGANIZATION' || $cat === 'ORGANISASI';
        }));

        // Jika salah satu kategori kosong, tetap tampilkan yang ada (tidak duplikasi)
        // Baris yang kosong akan tetap kosong atau bisa diisi placeholder
    }

    private function getPartnerImage($partner): string
    {
        $imageUrl = $partner->image_url;
        
        if (empty($imageUrl)) {
            return 'https://via.placeholder.com/150x80?text=No+Image';
        }
        
        // Jika URL lengkap, gunakan langsung
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }
        
        // Ekstrak nama file saja (jika ada path)
        $filename = basename($imageUrl);
        
        // Cek apakah file ada di local storage
        $localFilePath = public_path('storage/gambar_partner/' . $filename);
        
        if (file_exists($localFilePath)) {
            // File ada di lokal, gunakan asset()
            return asset('storage/gambar_partner/' . $filename);
        } else {
            // File tidak ada di lokal, gunakan URL ibekami.id sebagai fallback
            return 'https://ibekami.id/storage/gambar_partner/' . $filename;
        }
    }

    public function render()
    {
        return view('livewire.halaman-utama.mitra');
    }
}
