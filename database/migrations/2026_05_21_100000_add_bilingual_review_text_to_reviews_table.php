<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('review_id')->nullable()->after('name');
            $table->text('review_en')->nullable()->after('review_id');
        });

        foreach (DB::table('reviews')->get() as $review) {
            DB::table('reviews')
                ->where('id', $review->id)
                ->update([
                    'review_id' => $review->review,
                    'review_en' => $review->review,
                ]);
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('review');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('review')->nullable()->after('name');
        });

        foreach (DB::table('reviews')->get() as $review) {
            DB::table('reviews')
                ->where('id', $review->id)
                ->update([
                    'review' => $review->review_id ?? $review->review_en,
                ]);
        }

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['review_id', 'review_en']);
        });
    }
};
