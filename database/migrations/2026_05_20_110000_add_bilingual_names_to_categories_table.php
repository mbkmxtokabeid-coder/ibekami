<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_id', 150)->nullable()->after('type_id');
            $table->string('name_en', 150)->nullable()->after('name_id');
        });

        foreach (DB::table('categories')->get() as $category) {
            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'name_id' => $category->name,
                    'name_en' => $category->name,
                ]);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable()->after('type_id');
        });

        foreach (DB::table('categories')->get() as $category) {
            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'name' => $category->name_id ?? $category->name_en,
                ]);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_id', 'name_en']);
        });
    }
};
