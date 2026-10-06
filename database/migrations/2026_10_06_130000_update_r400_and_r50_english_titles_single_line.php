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
            ->where('title_en', 'like', '%R400%')
            ->update([
                'title_en' => 'Acrylic Laser Machine (R400)',
            ]);

        DB::table('machines')
            ->where('title_en', 'like', '%R50%')
            ->update([
                'title_en' => 'Acrylic Laser Machine (R50)',
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
