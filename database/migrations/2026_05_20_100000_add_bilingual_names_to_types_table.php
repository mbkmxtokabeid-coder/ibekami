<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->string('name_id', 100)->nullable()->after('id');
            $table->string('name_en', 100)->nullable()->after('name_id');
        });

        foreach (DB::table('types')->get() as $type) {
            DB::table('types')
                ->where('id', $type->id)
                ->update([
                    'name_id' => $type->name,
                    'name_en' => $type->name,
                ]);
        }

        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        foreach (DB::table('types')->get() as $type) {
            DB::table('types')
                ->where('id', $type->id)
                ->update([
                    'name' => $type->name_id ?? $type->name_en,
                ]);
        }

        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn(['name_id', 'name_en']);
        });
    }
};
