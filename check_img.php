<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Product::where('name_id', 'like', '%Chibi Keychain%')
    ->orWhere('name_en', 'like', '%Chibi Keychain%')
    ->first();

if (!$p) {
    echo "Product not found\n";
    exit;
}

echo "Name: " . $p->name_id . "\n";
echo "Image URL field in DB: " . json_encode($p->image_url) . "\n";
echo "Resolved URL: " . $p->getFirstImageUrl() . "\n";

$firstImage = is_array($p->image_url) ? $p->image_url[0] : (json_decode($p->image_url, true)[0] ?? null);
echo "First Image filename: " . $firstImage . "\n";

$storageCheck = \Illuminate\Support\Facades\Storage::disk('public')->exists('products/' . $firstImage);
echo "Storage::disk('public')->exists('products/'): " . ($storageCheck ? 'TRUE' : 'FALSE') . "\n";

$cachedUrl = \Illuminate\Support\Facades\Cache::get('prod_img_url:' . md5($firstImage));
echo "Cached in prod_img_url: " . var_export($cachedUrl, true) . "\n";

$path1 = storage_path('app/public/products/' . $firstImage);
$path2 = storage_path('app/public/gambar_produk/' . $firstImage);
$publicLinkPath1 = public_path('storage/products/' . $firstImage);
$publicLinkPath2 = public_path('storage/gambar_produk/' . $firstImage);

echo "Exists in storage/app/public/products/? " . (file_exists($path1) ? "YES ($path1)" : "NO ($path1)") . "\n";
echo "Exists in storage/app/public/gambar_produk/? " . (file_exists($path2) ? "YES ($path2)" : "NO ($path2)") . "\n";
$products = App\Models\Product::all();
$inProducts = 0;
$inGambarProduk = 0;
$inNeither = 0;

foreach ($products as $pr) {
    $imgs = $pr->image_url;
    if (is_string($imgs)) $imgs = json_decode($imgs, true);
    if (!empty($imgs) && is_array($imgs)) {
        $first = $imgs[0];
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists('products/' . $first)) {
            $inProducts++;
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists('gambar_produk/' . $first)) {
            $inGambarProduk++;
        } else {
            $inNeither++;
        }
    }
}

echo "Total products: " . count($products) . "\n";
echo "In products/: " . $inProducts . "\n";
echo "In gambar_produk/: " . $inGambarProduk . "\n";
echo "In neither: " . $inNeither . "\n";


