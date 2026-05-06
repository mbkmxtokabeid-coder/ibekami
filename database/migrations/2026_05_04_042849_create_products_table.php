<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('product_id')->primary();
            $table->string('name');
            $table->unsignedBigInteger('product_type')->default(0)->index();
            $table->unsignedBigInteger('category_type')->default(0)->index();
            $table->integer('discount')->default(0);
            $table->integer('price')->default(0);
            $table->longText('image_url')->nullable();
            $table->longText('detail')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('click_count')->default(0);
            $table->unsignedBigInteger('order_click_count')->default(0);
            $table->string('status')->nullable()->default('Tidak Aktif');
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
