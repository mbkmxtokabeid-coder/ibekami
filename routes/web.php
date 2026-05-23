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
                \Illuminate\Support\Facades\Artisan::call('config:cache');
                \Illuminate\Support\Facades\Artisan::call('route:cache');
                \Illuminate\Support\Facades\Artisan::call('view:cache');

                // Pemindaian & Konversi WebP Otomatis untuk Logo Kemitraan (PNG/JPG Lama)
                $partnerships = \App\Models\Partnership::all();
                $convertedCount = 0;
                $compressor = new \App\Services\ImageCompressor();

                foreach ($partnerships as $partner) {
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

                    // Abaikan jika sudah berformat webp atau svg
                    if (in_array($extension, ['webp', 'svg'])) {
                        continue;
                    }

                    // Periksa keberadaan file di public storage disk
                    $oldRelativePath = 'gambar_partner/' . $filename;
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldRelativePath)) {
                        $oldAbsPath = \Illuminate\Support\Facades\Storage::disk('public')->path($oldRelativePath);

                        // Buat nama berkas webp baru
                        $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                        $newRelativePath = 'gambar_partner/' . $newFilename;

                        try {
                            // Kompresi dan konversi ke webp
                            $compressor->compressToWebP($oldAbsPath, $newRelativePath);

                            // Hapus berkas lama
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldRelativePath);

                            // Perbarui kolom image_url di database
                            $partner->update([
                                'image_url' => $newFilename
                            ]);

                            $convertedCount++;
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Gagal konversi gambar partner {$filename} ke webp: " . $e->getMessage());
                        }
                    }
                }

                $message = 'Optimasi server berhasil dijalankan! Konfigurasi, rute, dan blade view telah di-cache.';
                if ($convertedCount > 0) {
                    $message .= " Berhasil mengonversi {$convertedCount} logo mitra lama menjadi format WebP modern.";
                }

                return back()->with('success', $message);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal optimasi: ' . $e->getMessage());
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
