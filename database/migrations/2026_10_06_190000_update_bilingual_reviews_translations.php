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
        $translations = [
            1 => 'This store is fantastic and highly recommended for anyone looking to make custom merchandise, tumblers, mugs, stickers, banners, and more. The designs they provide are very appealing and the product quality is top-notch.',
            2 => 'This shop is so creative! I bought goodie bags with lots of cute patterns, and you can even request custom designs. So don\'t hesitate to order, guys!',
            3 => 'The moment I saw the plaque from this store, I immediately loved it. The design is simple yet elegant. The size is just right—not too big, not too small—so it can be displayed anywhere. The material is premium and sturdy. Perfect as a keepsake or award.',
            4 => 'I recently ordered tumblers. At first, I was hesitant to buy in bulk, but once they arrived, I regretted not ordering more right away! I\'ll definitely order again for my next event. Thank you IB!',
            5 => 'Last week I had a sales competition event that needed a roll banner, and someone recommended this store for top quality. I decided to order, and it truly met my expectations. The roll banner turned out great, looking sleek with outstanding quality. I highly recommend this store to my friends!',
            7 => 'The print is sharp and the details really pop. The colors match the design perfectly with no fading, and the keychain material feels solid. Truly worth it for custom orders or gifts.',
            8 => 'The mug design turned out smooth and flawless, and the admin was very responsive. Excellent service!',
            9 => 'Extremely satisfied with the laser-cut calligraphy! The cuts are incredibly precise and neat down to the finest letters. The material is sturdy and adds a truly luxurious touch to the living room. Smooth and neat finish. Highly recommended for wall or desk decor.',
            10 => 'I\'m super satisfied with the plaque printing here because the quality is amazing and super sharp! The results never disappoint. Fast, friendly customer service. Highly recommended!!',
            11 => 'Ordered a roll banner here last week, totally worth it. The admin was very friendly too. We\'ll definitely be returning customers because the roll banner met all our team\'s expectations.',
        ];

        foreach ($translations as $id => $en) {
            DB::table('reviews')
                ->where('id', $id)
                ->update(['review_en' => $en]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
