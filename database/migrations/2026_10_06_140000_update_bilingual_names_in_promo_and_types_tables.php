<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update promo table
        $promoUpdates = [
            1 => ['name_id' => 'Suvenir / Cinderamata', 'name_en' => 'Souvenir / Merchandise'],
            2 => ['name_id' => 'Plakat',               'name_en' => 'Plaque'],
            8 => ['name_id' => 'Percetakan Digital',     'name_en' => 'Digital Printing'],
            9 => ['name_id' => 'Akrilik',                'name_en' => 'Acrylic'],
        ];

        foreach ($promoUpdates as $id => $data) {
            DB::table('promo')->where('id', $id)->update($data);
        }

        // Also update types table for catalog and navbar consistency
        $typeUpdates = [
            1 => ['name_id' => 'Suvenir / Cinderamata', 'name_en' => 'Souvenir / Merchandise'],
            2 => ['name_id' => 'Plakat',               'name_en' => 'Plaque'],
            8 => ['name_id' => 'Percetakan Digital',     'name_en' => 'Digital Printing'],
            9 => ['name_id' => 'Akrilik',                'name_en' => 'Acrylic'],
        ];

        foreach ($typeUpdates as $id => $data) {
            DB::table('types')->where('id', $id)->update($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
