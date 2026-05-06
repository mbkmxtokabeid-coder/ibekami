<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('banners');

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('media_url');          // path to image or webm video
            $table->string('media_type')->default('image'); // 'image' or 'video'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
