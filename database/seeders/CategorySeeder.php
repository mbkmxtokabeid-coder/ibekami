<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['type_id' => 1, 'name_id' => 'Tumbler', 'name_en' => 'Tumbler'],
            ['type_id' => 2, 'name_id' => 'Design Concept', 'name_en' => 'Design Concept'],
            ['type_id' => 3, 'name_id' => 'Design Digital Printing', 'name_en' => 'Design Digital Printing'],
            ['type_id' => 4, 'name_id' => 'Design Acrylic', 'name_en' => 'Design Acrylic'],
            ['type_id' => 1, 'name_id' => 'Portfolio of Merchandise', 'name_en' => 'Portfolio of Merchandise'],
            ['type_id' => 2, 'name_id' => 'Portfolio of Plaques', 'name_en' => 'Portfolio of Plaques'],
            ['type_id' => 4, 'name_id' => 'Portfolio of Acrylic', 'name_en' => 'Portfolio of Acrylic'],
            ['type_id' => 3, 'name_id' => 'Portofolio of Digital Printing', 'name_en' => 'Portfolio of Digital Printing'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['type_id' => $cat['type_id'], 'name_id' => $cat['name_id']],
                $cat
            );
        }
    }
}
