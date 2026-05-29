<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Tentukan lokasi bootstrap/vendor Laravel secara otomatis
// 1. Cek di level folder yang sama (untuk lokal / standard Laravel setup)
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    $laravel_path = __DIR__.'/..';
} 
// 2. Cek di folder sibling 'ibekami_bckend' (untuk production di shared hosting)
else if (file_exists(__DIR__.'/../ibekami_bckend/vendor/autoload.php')) {
    $laravel_path = __DIR__.'/../ibekami_bckend';
} 
// 3. Fallback default
else {
    $laravel_path = __DIR__.'/..';
}

// 1. Tentukan jika aplikasi sedang dalam mode maintenance...
if (file_exists($maintenance = $laravel_path.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Registrasi Composer autoloader...
require $laravel_path.'/vendor/autoload.php';

// 3. Bootstrap Laravel...
$app = require_once $laravel_path.'/bootstrap/app.php';

// 4. Hubungkan path public ke public_html agar CSS, JS, Gambar, dan Vite terbaca dari public_html
$app->usePublicPath(__DIR__);

// 5. Jalankan aplikasi dan tangani request...
$app->handleRequest(Request::capture());
