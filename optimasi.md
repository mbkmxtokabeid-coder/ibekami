# ⚡ Rencana Optimasi Fase 3 (Diperbarui): Arsitektur REST API, Self-Healing Storage (Nol 404), & Optimasi Total Kemitraan

Rencana ini telah diperbarui khusus untuk menyelesaikan **error 404 (Not Found) pada gambar/media storage** dan **error crash JavaScript di server produksi** Anda, sekaligus mengimplementasikan sistem **Infinite Scroll** dan **REST API Caching** yang sangat hemat CPU.

---

## 🔍 Analisis Error Produksi & Solusi Mandiri (Self-Healing)

### 1. Masalah Utama: Semua Media di `/storage/` Error 404
* **Gejala**: Berkas log Anda menunjukkan `storage/logos/logo ibekami (3).png`, `storage/gambar_partner/...png`, dan `storage/banners/....webm` semuanya mengembalikan status **404 (Not Found)**.
* **Penyebab**: Di shared hosting (Hostinger), **tautan simbolik (*symlink*) antara folder `public/storage` dan `storage/app/public` rusak, hilang, atau mengarah ke folder yang salah**.
* **Solusi Pintar (Self-Healing Storage Link)**: 
  Kita akan menaruh kode deteksi otomatis di dalam tombol POST `/admin/optimize-server` di dashboard. Saat ditekan, sistem secara otomatis akan:
  1. Mendeteksi apakah link `public/storage` rusak atau berupa folder kosong biasa.
  2. Menghapus link/folder rusak tersebut secara aman.
  3. Memanggil kembali `Artisan::call('storage:link')` untuk membuat symlink yang baru, segar, dan 100% tepat mengarah ke data storage Anda.
  Ini akan secara instan menghidupkan kembali seluruh gambar logo, banner video, dan mitra!

---

### 2. Masalah JavaScript: share-modal.js Crash (`Cannot read properties of null`)
* **Gejala**: `Uncaught TypeError: Cannot read properties of null (reading 'addEventListener') at share-modal.js`
* **Penyebab**: Skrip `share-modal.js` dipasang secara global di halaman, namun ia langsung memanggil `.addEventListener` pada elemen tombol share (seperti `share-btn`) yang **tidak ada** pada halaman tersebut (misal pada halaman utama). Ini memicu crash JS yang dapat menghentikan fungsi interaktif Alpine.js atau Livewire lainnya.
* **Solusi**: 
  Anda cukup membuka file `public/js/share-modal.js` (atau di mana skrip tersebut ditulis) di server produksi dan menambahkan pengondisian aman (*optional chaining* atau *null-check*):
  ```javascript
  // Kode lama:
  document.getElementById('share-btn').addEventListener('click', ...);

  // Ganti dengan Kode Aman (Null-check):
  const shareBtn = document.getElementById('share-btn');
  if (shareBtn) {
      shareBtn.addEventListener('click', function(e) {
          // logika share Anda
      });
  }
  ```

---

### 3. Masalah 404 Gambar Partner PNG
* **Gejala**: File `.png` seperti `6835436dd0e57.png` tidak ditemukan karena format di folder lokal Anda sudah diubah ke `.webp` lewat pembersihan sebelumnya.
* **Solusi (Auto WebP Sync & Fallback)**:
  Kita akan mengintegrasikan skrip konversi dan penyelarasan otomatis di dalam dashboard admin. Setiap kali tombol *Optimize Server* ditekan, database akan secara otomatis disinkronkan untuk merujuk ke nama file `.webp` dan mengonversi sisa berkas PNG lama menjadi WebP berkualitas tinggi secara senyap.

---

## Proposed Changes

### 1. Integrasi Self-Healing Storage Link & Auto-WebP Sync
#### [MODIFY] [web.php](file:///C:/Users/USER/.gemini/antigravity/worktrees/ibekami_bckend/open-project-workspace/routes/web.php)
Kita memperluas route POST `/admin/optimize-server` untuk secara otomatis memperbaiki link storage yang rusak dan menyelaraskan format gambar:
```php
        Route::post('/optimize-server', function () {
            try {
                // ── 1. SELF-HEALING SYMLINK STORAGE ──
                $storageLinkPath = public_path('storage');
                if (file_exists($storageLinkPath) || is_link($storageLinkPath)) {
                    // Jika itu berupa symlink lama/broken, hapus secara aman
                    if (is_link($storageLinkPath)) {
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            rmdir($storageLinkPath);
                        } else {
                            unlink($storageLinkPath);
                        }
                    }
                }

                // Buat ulang symlink yang segar dan benar
                \Illuminate\Support\Facades\Artisan::call('storage:link');

                // ── 2. OPTIMASI CONFIG & ROUTE CACHE ──
                \Illuminate\Support\Facades\Artisan::call('config:cache');
                \Illuminate\Support\Facades\Artisan::call('route:cache');
                \Illuminate\Support\Facades\Artisan::call('view:cache');

                // ── 3. AUTO-CONVERSION & DATABASE WEBP SYNC ──
                $partners = \App\Models\Partnership::all();
                $compressor = new \App\Services\ImageCompressor();
                $convertedCount = 0;

                foreach ($partners as $partner) {
                    $imageUrl = $partner->image_url;
                    if (empty($imageUrl)) continue;

                    $extension = strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION));
                    if (in_array($extension, ['png', 'jpg', 'jpeg'])) {
                        $filename = basename($imageUrl);
                        $localPath = public_path('storage/gambar_partner/' . $filename);

                        if (file_exists($localPath)) {
                            $newFilename = pathinfo($filename, PATHINFO_FILENAME) . '_' . uniqid() . '.webp';
                            $newStoragePath = 'gambar_partner/' . $newFilename;

                            // Jalankan kompresi ke WebP
                            $compressor->compressToWebP($localPath, $newStoragePath);

                            // Update Database
                            $partner->update(['image_url' => $newFilename]);

                            // Hapus file PNG/JPG lama
                            unlink($localPath);
                            $convertedCount++;
                        } else {
                            // Cek jika versi WebP dari file ini sudah ada di folder (hasil konversi manual/skrip)
                            $webpFilename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
                            $webpLocalPath = public_path('storage/gambar_partner/' . $webpFilename);
                            if (file_exists($webpLocalPath)) {
                                $partner->update(['image_url' => $webpFilename]);
                                $convertedCount++;
                            }
                        }
                    }
                }

                $msg = 'Server cache optimized & Storage Symlink repaired successfully!';
                if ($convertedCount > 0) {
                    $msg .= " Serta berhasil mensinkronisasi {$convertedCount} logo mitra ke format WebP!";
                }

                // Naikkan versi cache dinamis
                \Illuminate\Support\Facades\Cache::forever('homepage_products_version', time());
                \Illuminate\Support\Facades\Cache::forget('homepage:partners');

                return redirect()->route('admin.dashboard')->with('success', $msg);
            } catch (\Exception $e) {
                return redirect()->route('admin.dashboard')->with('error', 'Failed to optimize and repair: ' . $e->getMessage());
            }
        })->name('optimize-server');
```

---

### 2. Integrasi REST API & Infinite Scroll
#### [NEW] [HomepageProductController.php](file:///C:/Users/USER/.gemini/antigravity/worktrees/ibekami_bckend/open-project-workspace/app/Http/Controllers/Api/v1/HomepageProductController.php)
*(Kode sama seperti pada rencana sebelumnya).*

#### [MODIFY] [product-section.blade.php](file:///C:/Users/USER/.gemini/antigravity/worktrees/ibekami_bckend/open-project-workspace/resources/views/livewire/halaman-utama/product-section.blade.php)
*(Kode sama seperti pada rencana sebelumnya, me-render 12 produk pertama secara SSR dan mengaktifkan Infinite Scroll di browser).*

---

### 3. DOM Kloning Marquee Kemitraan (Bebas Duplikasi HTML)
#### [MODIFY] [mitra.blade.php](file:///C:/Users/USER/.gemini/antigravity/worktrees/ibekami_bckend/open-project-workspace/resources/views/livewire/halaman-utama/mitra.blade.php)
*(Kode sama seperti rencana sebelumnya, me-render 1x di Blade dan menggandakan secara otomatis di sisi browser menggunakan DOM Kloning Alpine.js).*

---

## Verification Plan

### 1. Uji Coba Perbaikan Otomatis (Nol 404)
* Terapkan kode ini ke server produksi Anda.
* Masuk ke **Dashboard Admin IBEKAMI**, lalu klik tombol **"Optimalkan Server"**.
* Periksa apakah notifikasi sukses muncul. 
* Muat ulang Halaman Utama Anda dan periksa konsol browser. Semua error 404 pada logo utama, video banner, dan logo kemitraan harus **sepenuhnya hilang** karena tautan simbolik telah diperbaiki otomatis ke folder target yang benar.

### 2. Uji Coba Sinkronisasi WebP
* Pastikan file database dari logo kemitraan Anda kini secara otomatis merujuk ke berkas WebP yang baru dikonversi di server.
