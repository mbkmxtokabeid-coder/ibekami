<?php

// Self-healing: Hapus cache routes & config jika ada di hosting untuk mencegah error 500
foreach ([
    __DIR__.'/../bootstrap/cache/config.php',
    __DIR__.'/../bootstrap/cache/routes-v7.php',
    __DIR__.'/../bootstrap/cache/routes.php'
] as $cacheFile) {
    if (file_exists($cacheFile)) {
        @unlink($cacheFile);
    }
}

// Self-healing: Hapus public/storage jika itu adalah symlink rusak untuk mengaktifkan fallback routing
$publicStorage = __DIR__.'/storage';
if (is_link($publicStorage) && !file_exists($publicStorage)) {
    @unlink($publicStorage);
}

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

// TAMBAHKAN BARIS INI:
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
