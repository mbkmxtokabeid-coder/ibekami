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
        // 1. Suvenir Kotak Tumbler / Tumbler Merchandise Box
        DB::table('products')
            ->where('product_id', 'f365f1f3-2c89-48af-8413-25a54b4b98a3')
            ->update([
                'name_id' => 'Suvenir Kotak Tumbler',
                'name_en' => 'Tumbler Merchandise Box',
            ]);

        // 2. Tumbler Olahraga Gunung / Mountain Sport Tumbler
        DB::table('products')
            ->where('product_id', '39bca92f-bc8e-4737-9617-a7e493310a3e')
            ->update([
                'name_id' => 'Tumbler Olahraga Gunung',
                'name_en' => 'Mountain Sport Tumbler',
            ]);

        // 3. Tumbler Olahraga 4.0/25 / Sport Tumbler 4.0/25
        DB::table('products')
            ->where('product_id', 'e0c101aa-dc9f-4171-ad78-0a59a3419ea2')
            ->update([
                'name_id' => 'Tumbler Olahraga 4.0/25',
                'name_en' => 'Sport Tumbler 4.0/25',
            ]);

        // 4. Other Sport Tumbler variants
        DB::table('products')
            ->where('product_id', '59aff158-ba72-461c-a0c9-a6afa0b706eb')
            ->update([
                'name_id' => 'Tumbler Olahraga Merah',
                'name_en' => 'Red Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', 'e21f459f-eb1e-490b-8f23-ba829a472490')
            ->update([
                'name_id' => 'Tumbler Olahraga Hitam',
                'name_en' => 'Black Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', '416ef55d-7fe9-417b-a225-fcca34b8f23a')
            ->update([
                'name_id' => 'Tumbler Olahraga Putih',
                'name_en' => 'White Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', 'ef0e2fa6-736d-4f01-984e-1e56d71d6616')
            ->update([
                'name_id' => 'Tumbler Olahraga Black Victory',
                'name_en' => 'Black Victory Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', '0317c1aa-0c49-4a58-8cde-2aaee8d212cb')
            ->update([
                'name_id' => 'Tumbler Olahraga Hitam',
                'name_en' => 'Black Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', 'cd55f720-cb55-4fbc-a0ea-91677ee6c43a')
            ->update([
                'name_id' => 'Tumbler Olahraga Kasual Merah',
                'name_en' => 'Red Casual Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', 'ebb94821-5124-4d61-9489-56569a80ee6d')
            ->update([
                'name_id' => 'Tumbler Olahraga Kasual Hitam',
                'name_en' => 'Black Casual Sport Tumbler',
            ]);

        DB::table('products')
            ->where('product_id', '9f4d6236-3b65-4d08-968c-38ab763ac775')
            ->update([
                'name_id' => 'Tumbler Olahraga Merah Kec. Medan Timur',
                'name_en' => 'Red Sport Tumbler Kec. Medan Timur',
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
