<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name_id' => 'Acrylic Display Premium',
                'name_en' => 'Acrylic Display Premium',
                'product_type' => 1,
                'category_type' => 1,
                'price' => 250000,
                'discount' => 0,
                'image_url' => json_encode(['https://ibekami.id/storage/gambar_produk/Acrylic_6811a5c76b71d.webp']),
                'detail_id' => json_encode(['Bahan' => 'Akrilik 5mm', 'Ukuran' => '20x30cm']),
                'detail_en' => json_encode(['Material' => 'Acrylic 5mm', 'Size' => '20x30cm']),
                'description_id' => 'Display stand acrylic premium dengan kualitas terbaik untuk keperluan display produk atau informasi.',
                'description_en' => 'Premium acrylic display stand with the best quality for product or information display needs.',
                'status' => 'Aktif',
            ],
            [
                'name_id' => 'Custom Merchandise Tumbler',
                'name_en' => 'Custom Merchandise Tumbler',
                'product_type' => 1,
                'category_type' => 1,
                'price' => 85000,
                'discount' => 10,
                'image_url' => json_encode(['https://ibekami.id/storage/gambar_produk/Merchandise_6811ac1035649.webp']),
                'detail_id' => json_encode(['Bahan' => 'Stainless Steel', 'Kapasitas' => '500ml']),
                'detail_en' => json_encode(['Material' => 'Stainless Steel', 'Capacity' => '500ml']),
                'description_id' => 'Tumbler custom dengan kualitas premium, cocok untuk souvenir atau merchandise perusahaan.',
                'description_en' => 'Custom tumbler with premium quality, perfect for souvenirs or corporate merchandise.',
                'status' => 'Aktif',
            ],
            [
                'name_id' => 'Digital Printing Banner',
                'name_en' => 'Digital Printing Banner',
                'product_type' => 1,
                'category_type' => 1,
                'price' => 45000,
                'discount' => 0,
                'image_url' => json_encode(['https://ibekami.id/storage/gambar_produk/Digital Printing_6811aa7d344bc.webp']),
                'detail_id' => json_encode(['Bahan' => 'Flexi Korea', 'Ukuran' => 'Custom']),
                'detail_en' => json_encode(['Material' => 'Flexi Korea', 'Size' => 'Custom']),
                'description_id' => 'Banner digital printing dengan kualitas cetak tinggi, tahan cuaca dan warna tidak mudah pudar.',
                'description_en' => 'Digital printing banner with high print quality, weather resistant and fade-resistant colors.',
                'status' => 'Aktif',
            ],
            [
                'name_id' => 'Produk Tidak Aktif',
                'name_en' => 'Inactive Product',
                'product_type' => 1,
                'category_type' => 1,
                'price' => 50000,
                'discount' => 0,
                'image_url' => json_encode(['https://ibekami.id/storage/gambar_produk/Merchandise_6811ac1035649.webp']),
                'detail_id' => json_encode(['Status' => 'Tidak Aktif']),
                'detail_en' => json_encode(['Status' => 'Inactive']),
                'description_id' => 'Produk ini tidak akan muncul di halaman utama karena statusnya tidak aktif.',
                'description_en' => 'This product will not appear on the homepage because it is inactive.',
                'status' => 'Tidak Aktif',
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert(array_merge($product, [
                'product_id' => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
