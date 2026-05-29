<?php
/**
 * IBEKAMI - Real Error Log Reader
 * Upload ke: public_html/debug.php
 * Akses sekali: https://ibekami.id/debug.php
 */

if (file_exists(__DIR__.'/../storage/logs/laravel.log')) {
    $logFile = __DIR__.'/../storage/logs/laravel.log';
} else {
    $logFile = __DIR__.'/../ibekami_bckend/storage/logs/laravel.log';
}

echo '<body style="background:#1a1a1a;color:#eee;font-family:monospace;padding:20px;line-height:1.5;">';
echo '<h2 style="color:#00ff00;border-bottom:1px solid #444;padding-bottom:10px;">=== IBEKAMI REAL ERROR LOG READER ===</h2>';

if (!file_exists($logFile)) {
    die('<p style="color:#ff5555;">File log laravel.log tidak ditemukan di: ' . htmlspecialchars($logFile) . '</p>');
}

$content = file_get_contents($logFile);
$lines = explode("\n", $content);

// Ambil 100 baris terakhir dari file log
$lastLines = array_slice($lines, -100);

echo '<pre style="background:#2a2a2a;padding:15px;border-radius:5px;overflow-x:auto;max-height:600px;overflow-y:auto;border:1px solid #444;">';
$foundError = false;
foreach ($lastLines as $line) {
    if (str_contains($line, '.ERROR:') || str_contains($line, '.CRITICAL:') || str_contains($line, 'Exception:')) {
        echo '<span style="color:#ff5555;font-weight:bold;background:#500;">' . htmlspecialchars($line) . '</span>' . "\n";
        $foundError = true;
    } else {
        echo htmlspecialchars($line) . "\n";
    }
}
echo '</pre>';

if (!$foundError) {
    echo '<p style="color:#aaaaaa;">ℹ️ Catatan: Tidak ada baris bertanda ".ERROR:" di 100 baris terakhir, namun teks di atas adalah log aktivitas terbaru di server Anda.</p>';
}

echo '</body>';
