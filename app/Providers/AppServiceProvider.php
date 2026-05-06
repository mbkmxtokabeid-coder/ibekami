<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // ═══════════════════════════════════════════════════════════════════
        // GLOBAL RATE LIMITERS (Safety Net)
        // ═══════════════════════════════════════════════════════════════════
        
        // Global Web Throttle - 120 requests per minute per IP
        // Ini adalah jaring pengaman terakhir untuk semua web routes
        // Lebih tinggi dari API karena web memiliki banyak assets (CSS, JS, images)
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.rate-limit', [
                        'message' => 'Terlalu banyak aktivitas. Silakan tunggu sebentar.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });
        
        // Global API Throttle - 60 requests per minute per user/IP
        // Standard rate limit untuk API endpoints
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many requests. Please try again later.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });
        
        // ═══════════════════════════════════════════════════════════════════
        // SPECIFIC RATE LIMITERS (Per Endpoint)
        // ═══════════════════════════════════════════════════════════════════
        
        // Rate Limiter untuk Halaman Katalog
        // 30 request per menit per IP
        // Melindungi dari bot scraping dan database flooding
        RateLimiter::for('katalog', function (Request $request) {
            return Limit::perMinute(30)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.rate-limit', [
                        'message' => 'Terlalu banyak permintaan. Silakan tunggu sebentar.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });

        // Rate Limiter untuk Detail Produk
        // 20 request per menit per IP
        // Mencegah data harvesting 394 produk
        RateLimiter::for('product-detail', function (Request $request) {
            return Limit::perMinute(20)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.rate-limit', [
                        'message' => 'Anda membuka terlalu banyak produk. Tunggu sebentar ya.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });

        // Rate Limiter untuk Language Switch
        // 10 request per menit per IP
        // Mencegah session flooding dan log pollution
        RateLimiter::for('language-switch', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.rate-limit', [
                        'message' => 'Terlalu sering mengganti bahasa. Tunggu sebentar.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });

        // Rate Limiter untuk WhatsApp Click Tracking
        // 5 request per menit per session
        // Mencegah distorsi data analitik dan double-click spam
        RateLimiter::for('whatsapp-tracking', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->session()->getId())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu banyak klik. Silakan tunggu sebentar.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });

        // Rate Limiter untuk Admin Area
        // 10 request per menit per IP
        // Proteksi ketat untuk admin panel
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.rate-limit', [
                        'message' => 'Terlalu banyak aktivitas admin. Silakan tunggu.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });

        // Rate Limiter untuk Login
        // 5 percobaan per menit per IP
        // Mencegah brute force attack
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->view('errors.rate-limit', [
                        'message' => 'Terlalu banyak percobaan login. Tunggu ' . ($headers['Retry-After'] ?? 60) . ' detik.',
                        'retry_after' => $headers['Retry-After'] ?? 60,
                    ], 429, $headers);
                });
        });
    }
}

