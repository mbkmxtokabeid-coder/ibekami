<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Tentukan jika aplikasi sedang dalam mode maintenance...
if (file_exists($maintenance = __DIR__.'/../ibekami_bckend/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Registrasi Composer autoloader...
require __DIR__.'/../ibekami_bckend/vendor/autoload.php';

// 3. Bootstrap Laravel...
$app = require_once __DIR__.'/../ibekami_bckend/bootstrap/app.php';

// 4. Hubungkan path public ke public_html agar CSS, JS, Gambar, dan Vite terbaca dari public_html
$app->usePublicPath(__DIR__);

// 5. Jalankan aplikasi dan tangani request...
$app->handleRequest(Request::capture());
