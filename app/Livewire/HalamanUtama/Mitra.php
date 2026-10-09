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
            return '';
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
            return '/storage/gambar_partner/' . $filename;
        }
        
        return '';
    }

    public function placeholder()
    {
        return <<<'HTML'
        <section class="py-16 px-4 bg-[#fff2e0] dark:bg-[#130D08]">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-lg mx-auto mb-10">
                    <div class="flex items-center justify-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] uppercase tracking-[0.2em] mb-2 sm:mb-3">
                        <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
                        {{ __('messages.trusted_together') }}
                        <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
                    </div>
                    <h2 class="font-['Poppins',sans-serif] text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#2C1A0E] dark:text-[#FDF5EC] tracking-tight leading-tight">{{ __('messages.our_partners') }}</h2>
                    <p class="text-[13px] text-[#7a6452] dark:text-[#9E8B7D] mt-2">{{ __('messages.trusted_by_institutions') }}</p>
                </div>
                <div class="flex flex-col gap-6 animate-pulse">
                    <div class="space-y-2">
                        <div class="h-5 w-24 bg-[#b35200]/10 mx-auto rounded-full"></div>
                        <div class="flex gap-4 overflow-hidden justify-center">
                            <div class="w-36 h-20 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/5 flex-shrink-0"></div>
                            <div class="w-36 h-20 bg-white dark:bg-[#231811] rounded-xl border border-[#b35200]/5 flex-shrink-0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        HTML;
    }

    public function render()
    {
        // Load all partners with valid images from cache -> DB
        $allPartners = Cache::remember('homepage:partners_v3', now()->addMinutes(30), function () {
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
                ->filter(function ($item) {
                    return !empty($item['image']);
                })
                ->values()
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
