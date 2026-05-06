<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        $machines = [
            [
                'title' => 'Cutting Laser R400',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685a4e0f5da13.webp',
            ],
            [
                'title' => 'Cutting Laser R50',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685a4e45371b8.webp',
            ],
            [
                'title' => 'Cutting Sticker',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685cf4d49a71a.webp',
            ],
            [
                'title' => 'Digital Printing Eco Solvent',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685a4e27a91f4.webp',
            ],
            [
                'title' => 'Print UV',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685a4dfdc46e1.webp',
            ],
            [
                'title' => 'Press Mug',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685a4dc454937.webp',
            ],
            [
                'title' => 'Wire Binding',
                'image_url' => 'https://ibekami.id/storage/machine_picture/685a4ded2e91b.webp',
            ],
        ];

        foreach ($machines as $machine) {
            Machine::create($machine);
        }
    }
}
