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
        // Fix 'Souvenir Box Tumbler' to Indonesian 'Suvenir Box Tumbler' and English 'Tumbler Merchandise Box'
        DB::table('products')
            ->where('product_id', 'f365f1f3-2c89-48af-8413-25a54b4b98a3')
            ->update([
                'name_id' => 'Suvenir Box Tumbler',
                'name_en' => 'Tumbler Merchandise Box',
            ]);

        // Clean trailing spaces
        DB::table('products')
            ->where('product_id', 'fdb8945c-06a2-464f-ac2b-b89e82af5ebd')
            ->update([
                'name_id' => 'Pena Anugerah Gemilang',
                'name_en' => 'Anugerah Gemilang Pen',
            ]);

        DB::table('products')
            ->where('product_id', 'c34c3db5-eec1-4575-a6c9-a7a5504c877e')
            ->update([
                'name_id' => 'Pena Bank Sumut',
                'name_en' => 'Bank Sumut Pen',
            ]);

        DB::table('products')
            ->where('product_id', '9b1ec1cd-335a-4ef5-b257-02c0a6d0bddc')
            ->update([
                'name_id' => 'X-Banner Kemenkes',
                'name_en' => 'Kemenkes X-Banner',
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
