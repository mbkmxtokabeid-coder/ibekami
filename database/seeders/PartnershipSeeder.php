<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('partnerships')->insert([
            [   
                'image_url' => "/images/company-logo/company-logo-1.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [   
                'image_url' => "/images/company-logo/company-logo-2.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [   
                'image_url' => "/images/company-logo/company-logo-3.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [   
                'image_url' => "/images/company-logo/company-logo-4.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [   
                'image_url' => "/images/company-logo/company-logo-5.png",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
