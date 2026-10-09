<?php

namespace App\Livewire\HalamanUtama;

use Livewire\Component;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;

class Ulasan extends Component
{
    public array $reviews = [];

    public function mount(): void
    {
        $this->loadReviews();
    }

    public function loadReviews(): void
    {
        $locale = app()->getLocale();

        // Load reviews from cache -> DB per locale
        $dbReviews = Cache::remember("homepage:reviews_{$locale}", now()->addMinutes(15), function () use ($locale) {
            return Review::query()
                ->orderBy('review_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($review) use ($locale) {
                    return [
                        'id' => $review->id,
                        'name' => $review->name,
                        'initials' => $this->getInitials($review->name),
                        'text' => $review->review,
                        'rating' => $review->star ?? 5,
                        'date' => $review->review_date 
                            ? \Carbon\Carbon::parse($review->review_date)->locale($locale)->diffForHumans() 
                            : ($locale === 'en' ? '1 month ago' : '1 bulan lalu'),
                    ];
                })
                ->toArray();
        });

        // Jika tidak ada review di database, gunakan dummy data
        if (empty($dbReviews)) {
            $this->reviews = $locale === 'en' ? [
                ['id' => 1, 'name' => 'Putri Andini', 'initials' => 'PA', 'text' => 'Very satisfied with the plaque printing here, the quality is great and sharp.', 'rating' => 5, 'date' => '1 month ago'],
                ['id' => 2, 'name' => 'Adelsa Putri', 'initials' => 'AP', 'text' => 'Laser cut calligraphy is very precise down to small details.', 'rating' => 5, 'date' => '1 month ago'],
                ['id' => 3, 'name' => 'Berkat Siagian', 'initials' => 'BS', 'text' => 'The mug design turned out smooth, admin is fast response.', 'rating' => 5, 'date' => '1 month ago'],
                ['id' => 4, 'name' => 'Putri Andini', 'initials' => 'PA', 'text' => 'Very satisfied with the plaque printing here, the quality is great and sharp.', 'rating' => 5, 'date' => '2 months ago'],
            ] : [
                ['id' => 1, 'name' => 'Putri Andini', 'initials' => 'PA', 'text' => 'Sangat puas cetak plakat di sini, hasil cetaknya bagus banget dan tajam.', 'rating' => 5, 'date' => '1 bulan lalu'],
                ['id' => 2, 'name' => 'Adelsa Putri', 'initials' => 'AP', 'text' => 'Laser cutting kaligrafinya sangat presisi sampai ke detail kecil.', 'rating' => 5, 'date' => '1 bulan lalu'],
                ['id' => 3, 'name' => 'Berkat Siagian', 'initials' => 'BS', 'text' => 'Hasil desain mug-nya mulus, admin juga fast respon.', 'rating' => 5, 'date' => '1 bulan lalu'],
                ['id' => 4, 'name' => 'Putri Andini', 'initials' => 'PA', 'text' => 'Sangat puas cetak plakat di sini, hasil cetaknya bagus banget dan tajam.', 'rating' => 5, 'date' => '2 bulan lalu'],
            ];
        } else {
            $this->reviews = $dbReviews;
        }
    }

    private function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }

    public function placeholder()
    {
        $locale = app()->getLocale();
        $title = $locale === 'en' ? 'What They Say' : 'Apa Kata Mereka?';
        $badge = $locale === 'en' ? 'Customer Reviews' : 'Ulasan Pelanggan';

        return <<<HTML
        <section class="py-16 px-4 bg-[#fdfaf7]">
            <div class="max-w-7xl mx-auto">
                <div class="mb-10 relative flex flex-col md:block">
                    <div class="text-center max-w-lg mx-auto">
                        <div class="flex items-center justify-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] uppercase tracking-[0.2em] mb-2 sm:mb-3">
                            <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
                            {$badge}
                            <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
                        </div>
                        <h2 class="font-['Poppins',sans-serif] text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#2C1A0E] tracking-tight leading-tight">
                            {$title}
                        </h2>
                    </div>

                    <!-- Card Rating Placeholder -->
                    <div class="mt-4 md:mt-0 md:absolute md:right-0 md:bottom-0 flex items-center justify-end shrink-0">
                        <div class="bg-white border border-[#b35200]/15 px-4 py-2.5 rounded-2xl shadow-sm flex items-center gap-3">
                            <div class="w-9 h-9 bg-yellow-400/20 text-yellow-500 rounded-xl flex items-center justify-center text-lg font-bold">★</div>
                            <div>
                                <p class="text-[10px] font-semibold text-[#886852] uppercase tracking-wider">Rating</p>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[15px] font-bold text-[#2C1A0E] leading-none">5.0</span>
                                    <div class="flex text-yellow-400 text-xs">★★★★★</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-6 overflow-hidden animate-pulse">
                    <div class="w-[280px] md:w-[350px] bg-white p-6 rounded-2xl border border-[#b35200]/5 h-48 flex-shrink-0"></div>
                    <div class="w-[280px] md:w-[350px] bg-white p-6 rounded-2xl border border-[#b35200]/5 h-48 flex-shrink-0"></div>
                    <div class="w-[280px] md:w-[350px] bg-white p-6 rounded-2xl border border-[#b35200]/5 h-48 flex-shrink-0"></div>
                </div>
            </div>
        </section>
        HTML;
    }

    public function render()
    {
        $this->loadReviews();

        return view('livewire.halaman-utama.ulasan', [
            'reviews' => $this->reviews,
        ]);
    }
}
