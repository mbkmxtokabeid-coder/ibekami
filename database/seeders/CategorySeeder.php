<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['type_id' => 1, 'name' => 'Tumbler'],
            ['type_id' => 2, 'name' => 'Design Concept'],
            ['type_id' => 3, 'name' => 'Design Digital Printing'],
            ['type_id' => 4, 'name' => 'Design Acrylic'],
            ['type_id' => 1, 'name' => 'Portfolio of Merchandise'],
            ['type_id' => 2, 'name' => 'Portfolio of Plaques'],
            ['type_id' => 4, 'name' => 'Portfolio of Acrylic'],
            ['type_id' => 3, 'name' => 'Portofolio of Digital Printing'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['type_id' => $cat['type_id'], 'name' => $cat['name']],
                $cat
            );
        }
    }
}
