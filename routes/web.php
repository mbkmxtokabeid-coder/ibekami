<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── Block PWA Files (return 404) ─────────────────────────────────────────────
// Mencegah browser/audit tool mendeteksi PWA dari file yang mungkin masih ada di server

Route::get('/manifest.json', fn() => abort(404));
// sw.js TIDAK di-404 — file ini adalah kill switch yang meng-unregister SW lama di browser pengunjung
// Route::get('/sw.js', fn() => abort(404)); // JANGAN aktifkan ini
Route::get('/service-worker.js', fn() => abort(404));
Route::get('/offline.html', fn() => abort(404));

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    $response = response(view('welcome'));
    // Hanya berikan cache header untuk pengunjung (guest) agar tidak membebani server
    if (!auth()->check()) {
        $response->header('Cache-Control', 'public, max-age=300, stale-while-revalidate=60');
    }
    return $response;
})->name('home');

Route::get('/katalog', function () {
    return view('katalog');
})->name('katalog')->middleware('throttle:katalog');

Route::get('/mesin', function () {
    return view('mesin');
})->name('mesin');

Route::get('/privacy-policy', \App\Livewire\PrivacyPolicy::class)
    ->name('privacy-policy');

Route::get('/katalog/{slug}', function ($slug) {
    // Generate cache key to prevent repetitive DB querying
    $cacheKey = 'route_product_seo_' . $slug . '_' . app()->getLocale();
    $product = cache()->remember($cacheKey, 600, function () use ($slug) {
        return \App\Models\Product::with(['type', 'category'])
            ->get()
            ->first(function ($product) use ($slug) {
                $slugId = \Illuminate\Support\Str::slug($product->name_id);
                $slugEn = \Illuminate\Support\Str::slug($product->name_en);
                return $slug === $slugId || $slug === $slugEn;
            });
    });

    if (!$product) {
        abort(404, 'Produk tidak ditemukan');
    }

    $title = $product->name . ' - IBEKAMI';
    $desc = $product->description ?? 'Produk berkualitas tinggi dengan desain yang menarik dan fungsional.';
    $desc = strip_tags(html_entity_decode($desc));
    $desc = \Illuminate\Support\Str::limit($desc, 160);

    return view('detail-katalog', [
        'slug' => $slug,
        'title' => $title,
        'meta_description' => $desc
    ]);
})->name('katalog.detail')->middleware('throttle:product-detail');

// ─── Language Switch ──────────────────────────────────────────────────────────

Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switch'])
    ->name('language.switch')
    ->middleware('throttle:language-switch');

// ─── Analytics Tracking ───────────────────────────────────────────────────────

Route::post('/track/whatsapp', [\App\Http\Controllers\AnalyticsController::class, 'trackWhatsAppClick'])
    ->name('analytics.whatsapp')
    ->middleware('throttle:whatsapp-tracking');

// ─── Admin Auth ───────────────────────────────────────────────────────────────

Route::get('/login-ibeka99', \App\Livewire\Admin\LoginForm::class)
    ->name('admin.login')
    ->middleware(['guest', 'throttle:login']);

Route::post('/login-ibeka99/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

// ─── Admin Panel (protected) ──────────────────────────────────────────────────

Route::prefix('admin')
    ->name('admin.')
    ->middleware([\App\Http\Middleware\AdminAuthenticated::class, 'throttle:admin'])
    ->group(function () {

        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        Route::post('/optimize-server', function () {
            try {
                // ── 1. SELF-HEALING SYMLINK STORAGE ──
                $storageError = null;
                try {
                    $storageLinkPath = public_path('storage');
                    
                    // Cek apakah link rusak (is_link tapi target tidak ada)
                    $isBrokenLink = is_link($storageLinkPath) && !file_exists($storageLinkPath);
                    
                    if (!file_exists($storageLinkPath) || $isBrokenLink) {
                        if ($isBrokenLink) {
                            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                                @rmdir($storageLinkPath);
                            } else {
                                @unlink($storageLinkPath);
                            }
                        }
                        
                        if (function_exists('symlink')) {
                            \Illuminate\Support\Facades\Artisan::call('storage:link');
                        } else {
                            $storageError = 'Folder public/storage tidak ditemukan (atau link rusak) dan fungsi symlink() dinonaktifkan di hosting Anda. Folder storage di public_html dibiarkan utuh demi keamanan aset Anda.';
                        }
                    } else {
                        $storageError = 'Folder public/storage sudah ada dan berfungsi dengan baik. Dibiarkan utuh demi keamanan.';
                    }
                } catch (\Throwable $e) {
                    $storageError = 'Gagal memproses storage link: ' . $e->getMessage();
                }

                // ── 2. OPTIMASI CONFIG & ROUTE CACHE (CLEAR AGAR TIDAK ERROR 500 DI SHARED HOSTING) ──
                // Caching config & route di web request sering kali error karena absolute paths / closure serialization.
                // Pembersihan (clear) adalah opsi paling aman dan stabil untuk shared hosting!
                \Illuminate\Support\Facades\Artisan::call('config:clear');
                \Illuminate\Support\Facades\Artisan::call('route:clear');
                \Illuminate\Support\Facades\Artisan::call('view:cache'); // Caching blade views aman & meningkatkan performa

                // ── 3. AUTO-CONVERSION & DATABASE WEBP SYNC ──
                $convertedCount = 0;
                $imageError = null;

                try {
                    if (\App\Services\ImageCompressor::isAvailable()) {
                        $partners = \App\Models\Partnership::all();
                        $compressor = new \App\Services\ImageCompressor();

                        foreach ($partners as $partner) {
                            $imageUrl = $partner->image_url;
                            if (empty($imageUrl)) {
                                continue;
                            }

                            // Abaikan jika berupa URL eksternal
                            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                continue;
                            }

                            $filename = basename($imageUrl);
                            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                            if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                                $oldRelativePath = 'gambar_partner/' . $filename;
                                
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldRelativePath)) {
                                    $oldAbsPath = \Illuminate\Support\Facades\Storage::disk('public')->path($oldRelativePath);
                                    
                                    // Generate WebP filename
                                    $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . uniqid() . '.webp';
                                    $newRelativePath = 'gambar_partner/' . $newFilename;

                                    try {
                                        // Compress and convert to webp
                                        $compressor->compressToWebP($oldAbsPath, $newRelativePath);

                                        // Delete old PNG/JPG file
                                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRelativePath);

                                        // Update Database to WebP
                                        $partner->update([
                                            'image_url' => $newFilename
                                        ]);

                                        $convertedCount++;
                                    } catch (\Throwable $e) {
                                        \Illuminate\Support\Facades\Log::error("Gagal kompresi logo partner {$filename}: " . $e->getMessage());
                                    }
                                } else {
                                    // Fallback Sync: Cek jika versi WebP dari file ini sudah ada di folder
                                    $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                                    $webpRelativePath = 'gambar_partner/' . $webpFilename;
                                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($webpRelativePath)) {
                                        $partner->update([
                                            'image_url' => $webpFilename
                                        ]);
                                        $convertedCount++;
                                    } else {
                                        // Cek juga jika versi WebP dengan suffix uniqid ada
                                        $storagePath = \Illuminate\Support\Facades\Storage::disk('public')->path('gambar_partner');
                                        if (is_dir($storagePath)) {
                                            $files = scandir($storagePath);
                                            $pattern = '/^' . preg_quote(pathinfo($filename, PATHINFO_FILENAME), '/') . '.*\.webp$/i';
                                            foreach ($files as $file) {
                                                if (preg_match($pattern, $file)) {
                                                    $partner->update([
                                                        'image_url' => $file
                                                    ]);
                                                    $convertedCount++;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    $imageError = 'Gagal memproses gambar mitra: ' . $e->getMessage();
                }

                // ── 4. BERSIHKAN FILE LOG & CACHE LOG VIEWER ──
                $logCleared = false;
                $logError = null;
                try {
                    $logPath = storage_path('logs/laravel.log');
                    if (file_exists($logPath)) {
                        // Mengosongkan isi file laravel.log dengan aman
                        file_put_contents($logPath, '');
                        $logCleared = true;
                    }

                    // Bersihkan cache pencarian & indeks Log-Viewer jika package aktif
                    if (class_exists(\Opcodes\LogViewer\Facades\LogViewer::class)) {
                        // LogViewer v3+ menggunakan clearFilesCache untuk membersihkan indeks berkas
                        if (method_exists(\Opcodes\LogViewer\Facades\LogViewer::class, 'clearFilesCache')) {
                            \Opcodes\LogViewer\Facades\LogViewer::clearFilesCache();
                        }
                    }
                } catch (\Throwable $e) {
                    $logError = 'Gagal membersihkan berkas log: ' . $e->getMessage();
                }

                $message = 'Optimasi server dan cache berhasil dilakukan secara aman!';
                if ($logCleared) {
                    $message = 'Optimasi server, pembersihan cache, dan penyegaran log error berhasil dilakukan secara aman!';
                }
                if ($convertedCount > 0) {
                    $message .= " Berhasil mensinkronisasi {$convertedCount} logo mitra ke format WebP!";
                }
                if ($storageError) {
                    $message .= " [Info Storage: {$storageError}]";
                }
                if ($imageError) {
                    $message .= " [Info Gambar: {$imageError}]";
                }
                if ($logError) {
                    $message .= " [Info Log: {$logError}]";
                }

                // Naikkan versi cache dinamis & hapus cache partners & hero banners
                \Illuminate\Support\Facades\Cache::forever('homepage_products_version', time());
                \Illuminate\Support\Facades\Cache::forget('homepage:partners');
                \Illuminate\Support\Facades\Cache::forget('homepage:hero_banner');
                \Illuminate\Support\Facades\Cache::forget('homepage:hero_banners_resolved');

                return back()->with('success', $message);
            } catch (\Throwable $e) {
                return back()->with('error', 'Gagal melakukan optimasi: ' . $e->getMessage());
            }
        })->name('optimize-server');

        // Frontend
        Route::prefix('frontend')->name('frontend.')->group(function () {
            Route::get('/product-type', \App\Livewire\Admin\Frontend\ProductType::class)->name('product-type');

            Route::get('/product-category', \App\Livewire\Admin\Frontend\ProductCategory::class)->name('product-category');

            Route::get('/product-list', \App\Livewire\Admin\Frontend\ProductList::class)->name('product-list');

            Route::get('/machine-list', \App\Livewire\Admin\Frontend\MachineList::class)->name('machine-list');
        });

        // Backend
        Route::prefix('backend')->name('backend.')->group(function () {
            Route::get('/partner-list', \App\Livewire\Admin\Backend\PartnerList::class)->name('partner-list');

            Route::get('/review-list', \App\Livewire\Admin\Backend\ReviewList::class)->name('review-list');

            Route::get('/banner-list', \App\Livewire\Admin\Backend\BannerList::class)->name('banner-list');
        });

        Route::get('/user-list', \App\Livewire\Admin\UserList::class)->name('user-list');
    });

// ─── Public REST API Endpoint ──────────────────────────────────────────────────
Route::get('/api/v1/homepage/products', [\App\Http\Controllers\Api\v1\HomepageProductController::class, 'index'])
    ->name('api.v1.homepage.products')
    ->middleware('throttle:60,1');

// ─── Storage Symlink Fallback for Shared Hosting ──────────────────────────────
// Melayani file secara langsung dari storage/app/public jika fungsi symlink dinonaktifkan di hosting
Route::get('/storage/{path}', function ($path) {
    $path = str_replace(['../', '..\\'], '', $path); // Prevent directory traversal
    $absPath = storage_path('app/public/' . $path);
    
    if (file_exists($absPath) && is_file($absPath)) {
        return response()->file($absPath, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
    abort(404);
})->where('path', '.*')->name('storage.local_fallback');
