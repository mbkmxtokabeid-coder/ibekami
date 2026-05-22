<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Indeks komposit untuk optimasi query halaman utama
            $table->index(['status', 'activated_at', 'created_at'], 'products_homepage_perf_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_homepage_perf_index');
        });
    }
};
