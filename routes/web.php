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
    return view('welcome');
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
    return view('detail-katalog', ['slug' => $slug]);
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
                    if (is_link($storageLinkPath)) {
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            @rmdir($storageLinkPath);
                        } else {
                            @unlink($storageLinkPath);
                        }
                    } elseif (is_dir($storageLinkPath)) {
                        // Jika public/storage adalah folder biasa, hapus jika kosong atau backup jika ada isinya
                        $files = array_diff(scandir($storageLinkPath), array('.', '..'));
                        if (empty($files)) {
                            @rmdir($storageLinkPath);
                        } else {
                            @rename($storageLinkPath, public_path('storage_backup_' . time()));
                        }
                    }

                    // Re-create storage symlink (jika fungsi symlink aktif)
                    if (function_exists('symlink')) {
                        \Illuminate\Support\Facades\Artisan::call('storage:link');
                    } else {
                        $storageError = 'Fungsi symlink() dinonaktifkan di hosting Anda. Hubungi penyedia hosting untuk mengaktifkannya atau buat storage link secara manual.';
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

                $message = 'Optimasi server dan cache berhasil dilakukan secara aman!';
                if ($convertedCount > 0) {
                    $message .= " Berhasil mensinkronisasi {$convertedCount} logo mitra ke format WebP!";
                }
                if ($storageError) {
                    $message .= " [Info Storage: {$storageError}]";
                }
                if ($imageError) {
                    $message .= " [Info Gambar: {$imageError}]";
                }

                // Naikkan versi cache dinamis & hapus cache partners
                \Illuminate\Support\Facades\Cache::forever('homepage_products_version', time());
                \Illuminate\Support\Facades\Cache::forget('homepage:partners');

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
