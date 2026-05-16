<?php
/**
 * TEMPORARY - HAPUS SETELAH DIGUNAKAN
 * Akses: https://ibekami.id/clear-cache.php?token=ibekami-clear-2026
 */
define('SECRET', 'ibekami-clear-2026');
if (($_GET['token'] ?? '') !== SECRET) {
    http_response_code(403);
    die('403 Forbidden.');
}

header('Content-Type: text/plain; charset=utf-8');

// public_html/ ada di dalam domain root, bukan di luar
// Struktur Hostinger: /home/u908433838/domains/ibekami.id/public_html/
// Jadi base path = dirname(__DIR__) = /home/u908433838/domains/ibekami.id
$base = dirname(__DIR__);

echo "Base path: $base\n\n";
echo "=== CLEARING LARAVEL CACHE ===\n\n";

// 1. Bootstrap cache
$cacheFiles = [
    "$base/bootstrap/cache/config.php",
    "$base/bootstrap/cache/routes-v7.php",
    "$base/bootstrap/cache/services.php",
    "$base/bootstrap/cache/packages.php",
    "$base/bootstrap/cache/events.php",
];
echo "1. Bootstrap cache:\n";
foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "   DELETED: " . basename($file) . "\n";
    } else {
        echo "   skip: " . basename($file) . "\n";
    }
}

// 2. Framework cache
echo "\n2. Framework cache:\n";
$dirs = [
    "$base/storage/framework/cache/data",
    "$base/storage/framework/views",
    "$base/storage/framework/sessions",
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) { echo "   skip (not found): $dir\n"; continue; }
    $count = 0;
    foreach (new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    ) as $f) {
        if ($f->isFile()) { unlink($f->getRealPath()); $count++; }
    }
    echo "   DELETED $count files in: " . basename($dir) . "\n";
}

// 3. Cek .env
echo "\n3. .env location check:\n";
$envPaths = [
    "$base/.env",
    dirname($base) . "/.env",
    "$base/public_html/../.env",
];
foreach ($envPaths as $p) {
    $real = realpath($p);
    echo "   " . $p . " → " . ($real ?: 'NOT FOUND') . "\n";
}

// 4. Cek file kritis
echo "\n4. Critical files:\n";
$critical = [
    'bootstrap/app.php'                        => "$base/bootstrap/app.php",
    'app/Http/Middleware/TrustProxies.php'     => "$base/app/Http/Middleware/TrustProxies.php",
    '.env'                                     => "$base/.env",
];
foreach ($critical as $label => $path) {
    echo "   $label: " . (file_exists($path) ? 'EXISTS ✓' : 'NOT FOUND ✗') . "\n";
    if (file_exists($path)) echo "     → " . realpath($path) . "\n";
}

// 5. Tampilkan struktur folder
echo "\n5. Folder structure at base:\n";
foreach (scandir($base) as $item) {
    if ($item === '.' || $item === '..') continue;
    $type = is_dir("$base/$item") ? '[DIR]' : '[FILE]';
    echo "   $type $item\n";
}

echo "\n=== DONE ===\n";
echo "HAPUS FILE INI: public_html/clear-cache.php\n";
