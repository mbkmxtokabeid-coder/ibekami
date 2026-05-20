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

    public function render()
    {
        return view('livewire.halaman-utama.ulasan');
    }
}
