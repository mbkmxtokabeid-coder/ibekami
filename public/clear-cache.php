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

// Path yang benar sesuai struktur server
// public_html/ → __DIR__
// ibekami_bckend/ → dirname(__DIR__) . '/ibekami_bckend'
$base = dirname(__DIR__) . '/ibekami_bckend';

echo "Laravel base: $base\n";
echo "Exists: " . (is_dir($base) ? 'YES ✓' : 'NO ✗') . "\n\n";

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

// 2. Framework cache, views, sessions
echo "\n2. Framework directories:\n";
$dirs = [
    'cache/data' => "$base/storage/framework/cache/data",
    'views'      => "$base/storage/framework/views",
    'sessions'   => "$base/storage/framework/sessions",
];
foreach ($dirs as $label => $dir) {
    if (!is_dir($dir)) { echo "   skip (not found): $label\n"; continue; }
    $count = 0;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    foreach ($it as $f) {
        if ($f->isFile()) { unlink($f->getRealPath()); $count++; }
    }
    echo "   DELETED $count files in: $label\n";
}

// 3. Cek file kritis
echo "\n3. Critical files check:\n";
$files = [
    '.env'                                 => "$base/.env",
    'bootstrap/app.php'                    => "$base/bootstrap/app.php",
    'app/Http/Middleware/TrustProxies.php' => "$base/app/Http/Middleware/TrustProxies.php",
    'vendor/autoload.php'                  => "$base/vendor/autoload.php",
];
foreach ($files as $label => $path) {
    $exists = file_exists($path);
    echo "   $label: " . ($exists ? 'EXISTS ✓' : 'NOT FOUND ✗') . "\n";
}

// 4. Cek .env values
echo "\n4. .env values:\n";
$envPath = "$base/.env";
if (file_exists($envPath)) {
    $watch = ['APP_ENV', 'APP_URL', 'APP_DEBUG', 'SESSION_SECURE_COOKIE', 'CACHE_PREFIX'];
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        foreach ($watch as $k) {
            if (str_starts_with(trim($line), $k . '=')) {
                echo "   " . trim($line) . "\n";
            }
        }
    }
} else {
    echo "   .env NOT FOUND — upload .env ke: $base/.env\n";
}

// 5. Cek TrustProxies terdaftar
echo "\n5. TrustProxies registration:\n";
$appPhp = "$base/bootstrap/app.php";
if (file_exists($appPhp)) {
    $content = file_get_contents($appPhp);
    echo "   " . (str_contains($content, 'TrustProxies') ? 'REGISTERED ✓' : 'NOT REGISTERED ✗') . "\n";
} else {
    echo "   bootstrap/app.php NOT FOUND\n";
}

// 6. Proxy headers saat ini
echo "\n6. Proxy headers:\n";
echo "   X-Forwarded-Proto : " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET') . "\n";
echo "   HTTPS             : " . ($_SERVER['HTTPS'] ?? 'off') . "\n";
echo "   SERVER_PORT       : " . ($_SERVER['SERVER_PORT'] ?? '?') . "\n";

echo "\n=== DONE ===\n";
echo "HAPUS FILE INI: public_html/clear-cache.php\n";
