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
        // Load reviews from cache -> DB
        $dbReviews = Cache::remember('homepage:reviews', now()->addMinutes(15), function () {
            return Review::query()
                ->orderBy('review_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'name' => $review->name,
                        'initials' => $this->getInitials($review->name),
                        'text' => $review->review,
                        'rating' => $review->star ?? 5,
                        'date' => $review->review_date ? \Carbon\Carbon::parse($review->review_date)->diffForHumans() : '1 bulan lalu',
                    ];
                })
                ->toArray();
        });

        // Jika tidak ada review di database, gunakan dummy data
        if (empty($dbReviews)) {
            $this->reviews = [
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
        return <<<'HTML'
        <div class="py-16 px-4 bg-[#fdfaf7]">
            <div class="max-w-7xl mx-auto">
                <div class="mb-10 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 text-[11px] font-bold text-[#ff9100] uppercase tracking-widest mb-2">
                        {{ __('messages.customer_reviews') }}
                        <span class="w-10 h-[1px] bg-[#ff9100]"></span>
                    </div>
                    <h2 class="font-['Playfair_Display'] text-3xl font-bold text-[#2C1A0E]">
                        {{ __('messages.what_they_say') }}
                    </h2>
                </div>
                <div class="flex gap-6 overflow-hidden animate-pulse">
                    <div class="w-[280px] md:w-[350px] bg-white p-6 rounded-2xl border border-[#ff9100]/5 h-48 flex-shrink-0"></div>
                    <div class="w-[280px] md:w-[350px] bg-white p-6 rounded-2xl border border-[#ff9100]/5 h-48 flex-shrink-0"></div>
                    <div class="w-[280px] md:w-[350px] bg-white p-6 rounded-2xl border border-[#ff9100]/5 h-48 flex-shrink-0"></div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.halaman-utama.ulasan');
    }
}
