<?php
/**
 * IBEKAMI - Diagnostic & Cache Cleaner Script
 * Upload file ini ke: public_html/fix.php
 * Akses sekali: https://ibekami.id/fix.php
 * HAPUS file ini setelah selesai!
 */

// Keamanan minimal - hapus baris ini jika tidak bisa akses
$secret = $_GET['key'] ?? '';
if ($secret !== 'ibekami2025') {
    die('Akses ditolak. Tambahkan ?key=ibekami2025 di URL');
}

$basePath = __DIR__ . '/../ibekami_bckend';
$results = [];

echo '<pre style="font-family:monospace;background:#1a1a1a;color:#00ff00;padding:20px;margin:0;">';
echo "=== IBEKAMI DIAGNOSTIC & CACHE CLEANER ===\n\n";

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "  ✅ OPcache/Memori PHP berhasil dibersihkan!\n";
}

// 1. Cek keberadaan file penting
echo "--- [1] CEK FILE PENTING ---\n";
$checkFiles = [
    '.env'                              => $basePath . '/.env',
    'vendor/autoload.php'              => $basePath . '/vendor/autoload.php',
    'bootstrap/app.php'                => $basePath . '/bootstrap/app.php',
    'storage/logs/ (writable?)'        => $basePath . '/storage/logs',
    'bootstrap/cache/ (writable?)'     => $basePath . '/bootstrap/cache',
];
foreach ($checkFiles as $label => $path) {
    $exists = file_exists($path);
    $writable = is_dir($path) ? is_writable($path) : true;
    echo sprintf("  %-40s %s\n", $label, $exists ? ($writable ? '✅ ADA' : '⚠️ ADA TAPI TIDAK WRITABLE') : '❌ TIDAK ADA');
}

// 2. Hapus bootstrap cache
echo "\n--- [2] HAPUS BOOTSTRAP CACHE ---\n";
$cacheDir = $basePath . '/bootstrap/cache';
$cacheFiles = glob($cacheDir . '/*.php');
if (empty($cacheFiles)) {
    echo "  ℹ️  Tidak ada file cache di bootstrap/cache/\n";
} else {
    foreach ($cacheFiles as $file) {
        $filename = basename($file);
        if (@unlink($file)) {
            echo "  ✅ Berhasil hapus: $filename\n";
        } else {
            echo "  ❌ Gagal hapus: $filename (periksa permission)\n";
        }
    }
}

// 3. Hapus view cache
echo "\n--- [3] HAPUS VIEW CACHE ---\n";
$viewCacheDir = $basePath . '/storage/framework/views';
$viewFiles = glob($viewCacheDir . '/*.php');
if (empty($viewFiles)) {
    echo "  ℹ️  Tidak ada view cache\n";
} else {
    $count = 0;
    foreach ($viewFiles as $file) {
        if (@unlink($file)) $count++;
    }
    echo "  ✅ Berhasil hapus $count view cache files\n";
}

// 4. Cek .env APP_KEY
echo "\n--- [4] CEK .ENV ---\n";
$envPath = $basePath . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    $appKey = '';
    $appEnv = '';
    $appUrl = '';
    if (preg_match('/^APP_KEY=(.+)$/m', $envContent, $m)) $appKey = trim($m[1]);
    if (preg_match('/^APP_ENV=(.+)$/m', $envContent, $m)) $appEnv = trim($m[1]);
    if (preg_match('/^APP_URL=(.+)$/m', $envContent, $m)) $appUrl = trim($m[1]);

    echo "  APP_ENV  : " . ($appEnv ?: '❌ TIDAK ADA') . "\n";
    echo "  APP_URL  : " . ($appUrl ?: '❌ TIDAK ADA') . "\n";
    echo "  APP_KEY  : " . (strlen($appKey) > 10 ? '✅ ADA (' . substr($appKey, 0, 15) . '...)' : '❌ TIDAK ADA / KOSONG') . "\n";
} else {
    echo "  ❌ File .env TIDAK ADA di server! Ini penyebab utama error!\n";
    echo "  Solusi: Upload file .env ke folder ibekami_bckend/\n";
}

// 5. Cek log terakhir
echo "\n--- [5] LOG ERROR TERAKHIR ---\n";
$logFile = $basePath . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    // Ambil 3000 karakter terakhir
    $tail = substr($logContent, -3000);
    // Cari baris yang ada "ERROR" atau "CRITICAL"
    $lines = explode("\n", $tail);
    $errorLines = array_filter($lines, fn($l) => str_contains($l, '[ERROR]') || str_contains($l, '[CRITICAL]') || str_contains($l, 'ERROR') || str_contains($l, 'Exception'));
    if (!empty($errorLines)) {
        echo "  Error terbaru:\n";
        foreach (array_slice($errorLines, -10) as $line) {
            echo "  " . htmlspecialchars(substr($line, 0, 200)) . "\n";
        }
    } else {
        echo "  Log ada tapi tidak ada error terdeteksi\n";
        echo "  5 baris terakhir:\n";
        foreach (array_slice($lines, -5) as $line) {
            echo "  " . htmlspecialchars($line) . "\n";
        }
    }
} else {
    echo "  ⚠️ File log tidak ada atau belum dibuat\n";
}

echo "\n=== SELESAI ===\n";
echo "Akses https://ibekami.id sekarang.\n";
echo "HAPUS file fix.php dari public_html setelah selesai!\n";
echo '</pre>';
