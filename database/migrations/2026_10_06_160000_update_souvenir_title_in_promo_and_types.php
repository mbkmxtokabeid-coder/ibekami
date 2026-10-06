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
        DB::table('promo')
            ->where('id', 1)
            ->update([
                'name_id' => 'Suvenir',
                'name_en' => 'Souvenir',
            ]);

        DB::table('types')
            ->where('id', 1)
            ->update([
                'name_id' => 'Suvenir',
                'name_en' => 'Souvenir',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
