<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    // Tangkap error utama sebelum Exception Handler Laravel yang menggunakan Facade berjalan
    // Ini akan menampilkan penyebab asli error (misal: db error, env error) secara rapi
    header('HTTP/1.1 500 Internal Server Error');
    echo '<div style="font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;padding:30px;background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;border-radius:8px;margin:30px auto;max-width:1000px;box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">';
    echo '<h1 style="margin-top:0;font-size:24px;border-bottom:1px solid #fca5a5;padding-bottom:10px;">🔴 ERROR UTAMA TERDETEKSI (IBEKAMI DIAGNOSTIC)</h1>';
    echo '<p style="font-size:16px;line-height:1.5;"><strong>Pesan Error:</strong> <span style="background:#fee2e2;padding:2px 6px;border-radius:4px;font-family:monospace;">' . htmlspecialchars($e->getMessage()) . '</span></p>';
    echo '<p style="font-size:14px;"><strong>Lokasi File:</strong> <code style="background:#fff;padding:2px 6px;border-radius:4px;border:1px solid #fee2e2;">' . htmlspecialchars($e->getFile()) . '</code> di baris <strong>' . $e->getLine() . '</strong></p>';
    echo '<h3 style="margin-bottom:5px;font-size:14px;color:#7f1d1d;">Stack Trace:</h3>';
    echo '<pre style="background:#fff;padding:15px;border-radius:6px;border:1px solid #fee2e2;overflow-x:auto;font-size:12px;color:#374151;line-height:1.4;max-height:400px;overflow-y:auto;font-family:SFMono-Regular,Consolas,Liberation Mono,Courier,monospace;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '<p style="font-size:12px;color:#6b7280;margin-top:15px;text-align:right;">IBEKAMI Diagnostic Helper Tool</p>';
    echo '</div>';
    // Jangan re-throw agar tidak memicu "A facade root has not been set"
}
