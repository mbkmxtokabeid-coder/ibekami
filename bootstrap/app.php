<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ── TrustProxies HARUS paling pertama ────────────────────────────────
        // Shared hosting (Hostinger, Niagahoster, dll) menggunakan reverse proxy.
        // Tanpa ini Laravel tidak tahu request aslinya HTTPS → redirect loop.
        $middleware->prepend(\App\Http\Middleware\TrustProxies::class);

        // ── Kompresi Respons ────────────────────────────────────────────────
        // Menghasilkan kompresi gzip untuk semua file teks dokumen (HTML, JSON, dll.)
        $middleware->prepend(\App\Http\Middleware\CompressResponse::class);

        // Redirect authenticated users to admin dashboard instead of /home
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
        
        // Add SetLocale middleware to web group
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\PreventPWA::class, // Prevent PWA install prompt
        ]);
        
        // Global API Throttle - Safety Net
        $middleware->api(prepend: [
            'throttle:api',
        ]);
        
        // Global Web Throttle - Safety Net
        $middleware->web(prepend: [
            'throttle:web',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
