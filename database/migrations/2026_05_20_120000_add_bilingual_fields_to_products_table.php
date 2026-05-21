<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_id', 200)->nullable()->after('product_id');
            $table->string('name_en', 200)->nullable()->after('name_id');
            $table->text('description_id')->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description_id');
            $table->longText('detail_id')->nullable()->after('description_en');
            $table->longText('detail_en')->nullable()->after('detail_id');
        });

        foreach (DB::table('products')->get() as $product) {
            DB::table('products')
                ->where('product_id', $product->product_id)
                ->update([
                    'name_id'         => $product->name,
                    'name_en'         => $product->name,
                    'description_id'  => $product->description,
                    'description_en'  => $product->description,
                    'detail_id'       => $product->detail,
                    'detail_en'       => $product->detail,
                ]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'detail']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable()->after('product_id');
            $table->text('description')->nullable();
            $table->longText('detail')->nullable();
        });

        foreach (DB::table('products')->get() as $product) {
            DB::table('products')
                ->where('product_id', $product->product_id)
                ->update([
                    'name'        => $product->name_id ?? $product->name_en,
                    'description' => $product->description_id ?? $product->description_en,
                    'detail'      => $product->detail_id ?? $product->detail_en,
                ]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_id', 'name_en', 'description_id', 'description_en', 'detail_id', 'detail_en']);
        });
    }
};
