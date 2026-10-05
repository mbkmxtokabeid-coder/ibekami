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
            if (!Schema::hasColumn('products', 'name_id')) {
                $table->string('name_id', 200)->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('products', 'name_en')) {
                $table->string('name_en', 200)->nullable()->after('name_id');
            }
            if (!Schema::hasColumn('products', 'description_id')) {
                $table->text('description_id')->nullable()->after('name_en');
            }
            if (!Schema::hasColumn('products', 'description_en')) {
                $table->text('description_en')->nullable()->after('description_id');
            }
            if (!Schema::hasColumn('products', 'detail_id')) {
                $table->longText('detail_id')->nullable()->after('description_en');
            }
            if (!Schema::hasColumn('products', 'detail_en')) {
                $table->longText('detail_en')->nullable()->after('detail_id');
            }
        });

        if (Schema::hasColumn('products', 'name')) {
            foreach (DB::table('products')->get() as $product) {
                DB::table('products')
                    ->where('product_id', $product->product_id)
                    ->update([
                        'name_id'         => $product->name_id ?? $product->name,
                        'name_en'         => $product->name_en ?? $product->name,
                        'description_id'  => $product->description_id ?? ($product->description ?? null),
                        'description_en'  => $product->description_en ?? ($product->description ?? null),
                        'detail_id'       => $product->detail_id ?? ($product->detail ?? null),
                        'detail_en'       => $product->detail_en ?? ($product->detail ?? null),
                    ]);
            }

            $columnsToDrop = array_values(array_filter(['name', 'description', 'detail'], fn($c) => Schema::hasColumn('products', $c)));
            if (!empty($columnsToDrop)) {
                Schema::table('products', function (Blueprint $table) use ($columnsToDrop) {
                    $table->dropColumn($columnsToDrop);
                });
            }
        }
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
