# ⚡ Analisis Diagnostics & Solusi Performa Lanjut (ibekami.id)

Dokumen ini menganalisis mengapa website https://ibekami.id/ terasa lambat saat pertama kali dimuat di mode **Incognito (Penyamaran)** dan saat perpindahan halaman (navigasi), serta memberikan solusi konkret berdasarkan hasil **Diagnostics Performa Hostinger** Anda.

---

## 🔍 Mengapa Skor Performa 90 tapi Incognito & Navigasi Tetap Lambat?

Ada perbedaan mendasar antara **Skor Sintetis (Lighthouse/PageSpeed)** dan **Pengalaman Nyata Pengguna (Real User Experience)**:

1. **Ilusi Cache di Browser Biasa**: Di browser reguler yang sudah pernah membuka website, semua aset berat (CSS, JS, Font, Logo WebP) telah tersimpan di memori browser Anda. Halaman terasa cepat karena browser tidak mengunduh aset tersebut lagi.
2. **Kondisi Kosong di Incognito**: Mode Incognito mensimulasikan pengunjung baru dengan **zero cache**. Browser harus mengunduh setiap kilobyte aset statis dari nol melalui jaringan internet.
3. **Full Page Reload Saat Navigasi**: Ketika pengguna berpindah halaman (misal: mengklik tombol **Katalog** atau **Mesin**), browser melakukan reload penuh. Di shared hosting, hal ini memaksa Laravel melakukan inisialisasi booting dari awal (memakan CPU tinggi) untuk merender halaman baru, yang berujung pada **TTFB (Time to First Byte)** yang sangat tinggi (1,5 - 2,5 detik).

---

## 📊 Bedah Hasil Diagnostics Performa Hostinger & Solusinya

Berikut penjelasan detail per item diagnosa yang berwarna merah/oranye di laporan Hostinger Anda, beserta cara memperbaikinya:

### 1. Document Request Latency & Avoid Multiple Page Redirects (Skor: 0 - MERAH)
*   **Masalah**: Permintaan dokumen HTML pertama (TTFB) sangat lambat dan ada rantai pengalihan (redirect) yang membuang waktu.
*   **Analisis**: 
    - Setiap redirect (misalnya dari `http://` ke `https://` atau `non-www` ke `www`) menambah 1 *round-trip* koneksi (sekitar 200-400ms).
    - Teks HTML ditransfer dari server Hostinger tanpa dikompresi (tanpa Gzip/Brotli).
*   **Solusi**:
    1. Pastikan domain utama Anda di hPanel Hostinger diatur langsung ke **HTTPS** dan satu varian saja (disarankan tanpa `www` atau dengan `www` secara konsisten).
    2. Aktifkan **Gzip Text Compression** pada file `.htaccess` agar ukuran file HTML menyusut hingga 70-80% sebelum dikirim.

### 2. Use Efficient Cache Lifetimes (Skor: 50 - ORANYE)
*   **Masalah**: Aset statis seperti gambar WebP, font WOFF2, CSS, dan JS tidak memiliki instruksi durasi cache yang jelas untuk browser (*Cache-Control* / *Expires* headers).
*   **Analisis**: Karena server tidak mengirimkan header cache, browser di mode reguler pun akan sering memvalidasi ulang aset ke server, membebani bandwidth shared hosting Anda.
*   **Solusi**: Tambahkan modul `mod_expires` di `.htaccess` untuk memaksa browser menyimpan aset statis seperti gambar, CSS, JS, dan font selama **1 tahun** (aman karena nama file CSS/JS dari Vite menggunakan sistem hashing unik).

### 3. Render Blocking Requests & Network Dependency Tree (Skor: 50 - ORANYE)
*   **Masalah**: Aset CSS dan JS memblokir browser untuk merender halaman secara visual terlebih dahulu.
*   **Solusi**: Gunakan pemuatan non-blocking. Untuk Livewire, pastikan script dimuat menggunakan atribut `defer`.

---

## 🚀 Solusi Ultimate: Memperbaiki Lambatnya Navigasi (Home ➔ Katalog ➔ Mesin)

Solusi paling ampuh untuk membuat perpindahan halaman terasa instan (di bawah **0.2 detik**), seolah-olah website adalah aplikasi mobile (SPA), adalah menggunakan fitur bawaan **Livewire 3**: `wire:navigate`.

### Bagaimana `wire:navigate` Bekerja?
Secara default, tag `<a href="/katalog">` akan memicu reload penuh satu halaman. 
Jika kita menambahkan atribut `wire:navigate` menjadi `<a href="/katalog" wire:navigate>`:
1. Livewire akan mencegat klik tersebut dan mengambil konten halaman tujuan di latar belakang via AJAX.
2. Livewire secara instan mengganti bagian `<body>` halaman saat ini dengan konten halaman baru tanpa berkedip dan tanpa mengunduh ulang CSS, JS, maupun Font!
3. Pengalaman pengguna berpindah halaman menjadi **instan dan mulus** bahkan pada koneksi internet lambat.

---

## 🛠️ Langkah-Langkah Eksekusi (Action Plan)

Kita akan melakukan 3 tindakan utama:

### Langkah A: Mengoptimalkan Navigasi Utama (Navbar & Footer)
Kita akan memodifikasi menu navigasi di `navbar.blade.php` dan `footer.blade.php` agar menggunakan `wire:navigate` pada link-link internal.

> [!IMPORTANT]
> Tautan eksternal (seperti WhatsApp, Instagram, TikTok) **TIDAK** boleh menggunakan `wire:navigate`. Tautan internal saja yang menuju ke `/`, `/katalog`, `/mesin`, dan `/privacy-policy`.

### Langkah B: Konfigurasi Lengkap `.htaccess` untuk Gzip & Caching
Kita akan memperbarui `.htaccess` di server Hostinger Anda untuk mengaktifkan kompresi teks Gzip dan masa pakai cache 1 tahun.

### Langkah C: Aktivasi Laravel Optimization Cache di Server Produksi
Anda harus menjalankan perintah optimasi Laravel agar backend tidak melakukan proses kompilasi berulang kali pada shared hosting. Karena shared hosting tidak memiliki SSH, kami akan menyiapkan rute khusus `/admin/optimize-server` yang dapat diakses dari dashboard admin Anda.
