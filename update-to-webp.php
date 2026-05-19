<?php

/**
 * Update Database to Use WebP Images
 * Run: php update-to-webp.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔄 Updating database to use WebP images...\n\n";

// Update Products
echo "📦 Updating products table...\n";
$productsUpdated = DB::table('products')
    ->whereNotNull('image_url')
    ->update([
        'image_url' => DB::raw("REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')")
    ]);
echo "   ✅ Updated {$productsUpdated} products\n\n";

// Update Banners
echo "🎨 Updating banners table...\n";
try {
    $bannersUpdated = DB::table('banners')
        ->where('media_type', 'image')
        ->whereNotNull('media_url')
        ->update([
            'media_url' => DB::raw("REPLACE(REPLACE(REPLACE(media_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')")
        ]);
    echo "   ✅ Updated {$bannersUpdated} banners\n\n";
} catch (\Exception $e) {
    echo "   ⚠️  Banners table not found or error: " . $e->getMessage() . "\n\n";
}

// Update Partnerships
echo "🤝 Updating partnerships table...\n";
try {
    $partnersUpdated = DB::table('partnerships')
        ->whereNotNull('image_url')
        ->update([
            'image_url' => DB::raw("REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')")
        ]);
    echo "   ✅ Updated {$partnersUpdated} partnerships\n\n";
} catch (\Exception $e) {
    echo "   ⚠️  Partnerships table not found or error: " . $e->getMessage() . "\n\n";
}

// Update Machines
echo "🔧 Updating machines table...\n";
try {
    $machinesUpdated = DB::table('machines')
        ->whereNotNull('image_url')
        ->update([
            'image_url' => DB::raw("REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')")
        ]);
    echo "   ✅ Updated {$machinesUpdated} machines\n\n";
} catch (\Exception $e) {
    echo "   ⚠️  Machines table not found or error: " . $e->getMessage() . "\n\n";
}

// Update Types
echo "📂 Updating types table...\n";
try {
    $typesUpdated = DB::table('types')
        ->whereNotNull('image_url')
        ->update([
            'image_url' => DB::raw("REPLACE(REPLACE(REPLACE(image_url, '.jpg', '.webp'), '.jpeg', '.webp'), '.png', '.webp')")
        ]);
    echo "   ✅ Updated {$typesUpdated} types\n\n";
} catch (\Exception $e) {
    echo "   ⚠️  Types table not found or error: " . $e->getMessage() . "\n\n";
}

// Verification
echo "═══════════════════════════════════════════════════════════\n";
echo "📊 Verification:\n\n";

$webpCount = DB::table('products')
    ->where('image_url', 'LIKE', '%.webp%')
    ->count();
echo "   Products with WebP: {$webpCount}\n";

$oldFormatCount = DB::table('products')
    ->where(function($query) {
        $query->where('image_url', 'LIKE', '%.jpg%')
              ->orWhere('image_url', 'LIKE', '%.jpeg%')
              ->orWhere('image_url', 'LIKE', '%.png%');
    })
    ->count();
echo "   Products with old format: {$oldFormatCount}\n\n";

echo "═══════════════════════════════════════════════════════════\n";
echo "✨ Database update complete!\n\n";

if ($oldFormatCount > 0) {
    echo "⚠️  Warning: {$oldFormatCount} products still have old format images.\n";
    echo "   This might be because WebP files don't exist for those images.\n\n";
}

echo "📝 Next steps:\n";
echo "   1. Clear cache: php artisan cache:clear\n";
echo "   2. Test website to ensure images load correctly\n";
echo "   3. If everything works, you can delete original JPG/PNG files\n\n";
