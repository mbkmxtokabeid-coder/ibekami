<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Souvenir / Merchandise', 'image_url' => null],
            ['name' => 'Plaque / Plakat',        'image_url' => null],
            ['name' => 'Digital Printing',       'image_url' => null],
            ['name' => 'Acrylic',                'image_url' => null],
            ['name' => 'Stempel',                'image_url' => null],
            ['name' => 'Booth',                  'image_url' => null],
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
