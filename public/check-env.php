<?php
/**
 * TEMPORARY - HAPUS SETELAH DIGUNAKAN
 * Akses: https://ibekami.id/check-env.php
 */
header('Content-Type: text/plain; charset=utf-8');

$base = dirname(__DIR__) . '/ibekami_bckend';

echo "=== ENV & CONFIG CHECK ===\n\n";

// Cek .env
$envPath = "$base/.env";
echo "1. .env file:\n";
echo "   Path  : $envPath\n";
echo "   Exists: " . (file_exists($envPath) ? 'YES ✓' : 'NO ✗ — UPLOAD .env KE SINI!') . "\n";

if (file_exists($envPath)) {
    echo "\n   Key values:\n";
    $watch = ['APP_ENV','APP_URL','APP_DEBUG','SESSION_SECURE_COOKIE','CACHE_PREFIX','DB_DATABASE'];
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        foreach ($watch as $k) {
            if (str_starts_with(trim($line), $k . '=')) {
                echo "   " . trim($line) . "\n";
            }
        }
    }
}

// Cek semua file kritis
echo "\n2. Critical files:\n";
$critical = [
    'bootstrap/app.php'                    => "$base/bootstrap/app.php",
    'app/Http/Middleware/TrustProxies.php' => "$base/app/Http/Middleware/TrustProxies.php",
    'vendor/autoload.php'                  => "$base/vendor/autoload.php",
    'storage/framework/sessions'           => "$base/storage/framework/sessions",
    'storage/framework/cache'              => "$base/storage/framework/cache",
    'storage/framework/views'              => "$base/storage/framework/views",
];
foreach ($critical as $label => $path) {
    $exists = file_exists($path) || is_dir($path);
    echo "   $label: " . ($exists ? 'OK ✓' : 'MISSING ✗') . "\n";
}

// Cek TrustProxies terdaftar
echo "\n3. TrustProxies in bootstrap/app.php:\n";
$appPhp = "$base/bootstrap/app.php";
if (file_exists($appPhp)) {
    $c = file_get_contents($appPhp);
    echo "   " . (str_contains($c, 'TrustProxies') ? 'REGISTERED ✓' : 'NOT REGISTERED ✗') . "\n";
}

// Proxy headers
echo "\n4. Server headers:\n";
echo "   X-Forwarded-Proto : " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'NOT SET') . "\n";
echo "   HTTPS             : " . ($_SERVER['HTTPS'] ?? 'off') . "\n";

// Storage symlink
echo "\n5. Storage symlink:\n";
$symlink = __DIR__ . '/storage';
if (is_link($symlink)) {
    echo "   public_html/storage → " . readlink($symlink) . " ✓\n";
} elseif (is_dir($symlink)) {
    echo "   public_html/storage: is a directory (not symlink)\n";
} else {
    echo "   public_html/storage: NOT FOUND\n";
}

echo "\n=== DONE ===\n";
echo "HAPUS FILE INI: public_html/check-env.php\n";
