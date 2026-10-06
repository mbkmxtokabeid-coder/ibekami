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
        DB::table('machines')
            ->where('id', 6)
            ->update([
                'title_en' => 'Eco-Solvent Banner Machine',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('machines')
            ->where('id', 6)
            ->update([
                'title_en' => 'Eco-Solvent Banner Printing Machine',
            ]);
    }
};
