<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name_id' => 'Souvenir / Merchandise',
                'name_en' => 'Souvenir / Merchandise',
                'image_url' => null,
            ],
            [
                'name_id' => 'Plaque / Plakat',
                'name_en' => 'Plaque / Award',
                'image_url' => null,
            ],
            [
                'name_id' => 'Digital Printing',
                'name_en' => 'Digital Printing',
                'image_url' => null,
            ],
            [
                'name_id' => 'Acrylic',
                'name_en' => 'Acrylic',
                'image_url' => null,
            ],
            [
                'name_id' => 'Stempel',
                'name_en' => 'Stamp',
                'image_url' => null,
            ],
            [
                'name_id' => 'Booth',
                'name_en' => 'Booth',
                'image_url' => null,
            ],
        ];

        foreach ($types as $type) {
            Type::updateOrCreate(
                ['name_id' => $type['name_id']],
                $type
            );
        }
    }
}
