<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partnerships', function (Blueprint $table) {
            // Pindah category ke setelah id (sebelum image_url)
            $table->string('category')->default('BUMN')->after('id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('partnerships', function (Blueprint $table) {
            $table->string('category')->default('BUMN')->after('image_url')->change();
        });
    }
};
