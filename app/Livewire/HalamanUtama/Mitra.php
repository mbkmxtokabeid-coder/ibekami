<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Partnership;
use Illuminate\Support\Facades\Cache;

class Mitra extends Component
{
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

    public function placeholder()
    {
        return <<<'HTML'
        <div class="py-16 px-4 bg-[#fff2e0] animate-pulse">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-lg mx-auto mb-10">
                    <div class="h-3 w-32 bg-[#ff9100]/20 mx-auto rounded mb-3"></div>
                    <div class="h-8 w-64 bg-[#3d2b1f]/20 mx-auto rounded"></div>
                </div>
                <div class="flex flex-col gap-6">
                    <div class="space-y-2">
                        <div class="h-5 w-24 bg-[#ff9100]/10 mx-auto rounded-full"></div>
                        <div class="flex gap-4 overflow-hidden justify-center">
                            <div class="w-36 h-20 bg-white rounded-xl border border-[#ff9100]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white rounded-xl border border-[#ff9100]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white rounded-xl border border-[#ff9100]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white rounded-xl border border-[#ff9100]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white rounded-xl border border-[#ff9100]/5 flex-shrink-0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        // Load all partners from cache -> DB
        $allPartners = Cache::remember('homepage:partners', now()->addMinutes(30), function () {
            return Partnership::query()
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($partner) {
                    return [
                        'id' => $partner->id,
                        'category' => $partner->category ?? 'BUMN',
                        'image' => $this->getPartnerImage($partner),
                    ];
                })
                ->toArray();
        });

        // Separate by category (case-insensitive) - STRICT MODE
        $partnersBumn = array_values(array_filter($allPartners, function ($partner) {
            return strtoupper(trim($partner['category'])) === 'BUMN';
        }));

        $partnersOrganization = array_values(array_filter($allPartners, function ($partner) {
            $cat = strtoupper(trim($partner['category']));
            return $cat === 'ORGANIZATION' || $cat === 'ORGANISASI';
        }));

        return view('livewire.halaman-utama.mitra', [
            'partnersBumn' => $partnersBumn,
            'partnersOrganization' => $partnersOrganization
        ]);
    }
}
