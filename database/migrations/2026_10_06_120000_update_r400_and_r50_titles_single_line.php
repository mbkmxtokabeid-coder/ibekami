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
            ->where('title_id', 'like', '%R400%')
            ->orWhere('title', 'like', '%R400%')
            ->update([
                'title'    => 'Mesin Laser Akrilik (R400)',
                'title_id' => 'Mesin Laser Akrilik (R400)',
            ]);

        DB::table('machines')
            ->where('title_id', 'like', '%R50%')
            ->orWhere('title', 'like', '%R50%')
            ->update([
                'title'    => 'Mesin Laser Akrilik (R50)',
                'title_id' => 'Mesin Laser Akrilik (R50)',
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
