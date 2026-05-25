# 📄 Panduan Optimasi Latensi Permintaan Dokumen (Document Latency)
*Berdasarkan Dokumentasi Resmi Google Chrome Developer & Pengukuran Core Web Vitals*

Latensi permintaan dokumen (*Document Latency*) mengacu pada waktu yang dibutuhkan oleh browser untuk meminta, memproses, dan menerima dokumen HTML awal (dokumen utama) dari server web ketika pengguna pertama kali mengakses halaman.

Karena browser **tidak dapat memulai pemuatan aset apa pun** (CSS, JavaScript, font, gambar) sebelum dokumen HTML awal ini selesai di-download dan diparsing, meminimalkan latensi dokumen adalah **fondasi paling krusial** dari seluruh optimasi performa web.

---

## ⚠️ 3 Kondisi Pemicu Masalah Latensi Dokumen (Google Chrome DevTools)

Google Chrome DevTools dan Lighthouse akan menandai dokumen web Anda mengalami masalah latensi jika terdeteksi salah satu dari 3 kondisi berikut:

1.  **Terjadi Pengalihan (Redirects)**: Ada proses *routing* tambahan yang memaksa browser melakukan *handshake* berulang sebelum mencapai halaman utama.
2.  **Waktu Respons Server Lambat (TTFB > 600ms)**: Server membutuhkan waktu lebih dari 600 milidetik untuk menyusun dan mengirimkan byte pertama data dokumen.
3.  **Respons Teks Tidak Dikompresi**: Dokumen HTML dikirimkan dalam ukuran byte mentah penuh tanpa memanfaatkan algoritma kompresi data jaringan.

---

## 🛠️ Panduan Praktis & Cara Implementasi Optimasi

### 1. Hindari Pengalihan (Avoid Redirects)
Setiap kali pengalihan HTTP terjadi (seperti status kode 301 untuk pengalihan permanen atau 302 untuk sementara), browser harus menghentikan proses unduh saat ini, mengurai header lokasi yang baru, lalu memulai ulang permintaan TCP/TLS jabat-tangan baru. Di jaringan seluler berkabel nirkabel yang lambat (3G/4G), hal ini dapat memicu penundaan pemuatan hingga beberapa detik.

#### 🎯 Langkah Implementasi Kelas Industri:
*   **Audit Semua Tautan Internal**: Pastikan menu navigasi, tombol CTA, *footer*, dan tautan silang dalam situs langsung mengarah ke URL tujuan akhir yang paling baru dan menggunakan skema HTTPS yang tepat.
    *   *Buruk*: Menggunakan tautan `http://ibekami.id/katalog` (memicu redirect ke HTTPS).
    *   *Baik*: Menggunakan tautan `https://ibekami.id/katalog`.
*   **Redirect Satu Langkah (Single-Hop Redirects)**: Jika pengalihan mutlak diperlukan (misalnya mengarahkan domain lama atau menangani normalisasi domain), pastikan pengalihan tersebut bersifat langsung dan terjadi di level server web terluar (seperti `.htaccess` Apache atau blok server Nginx), bukan di level routing backend PHP.
*   **Gunakan Desain Web Responsif**: Jangan membuat pengalihan URL dinamis terpisah untuk pengguna mobile (misalnya dari `domain.com` dialihkan ke `m.domain.com`). Pertahankan satu URL tunggal dan gunakan TailwindCSS atau CSS Media Queries responsif agar halaman otomatis beradaptasi dengan lebar layar.

---

### 2. Kurangi Waktu Respons Server (Optimize TTFB)
*Time to First Byte* (TTFB) mengukur waktu antara permintaan pertama browser hingga diterimanya byte data pertama dari server. Jika TTFB di atas 600ms, browser akan mendeteksi layar putih kosong (*blank white screen*) yang menyebabkan rasio pentalan (*bounce rate*) pengunjung baru melonjak tinggi.

#### 🎯 Teknik Optimasi Khusus di Laravel:
*   **Eager Loading (Mencegah Masalah N+1 Queries)**:
    Pastikan relasi database dimuat secara bersamaan menggunakan metode `with()` di Eloquent. Hal ini memotong puluhan kueri yang tidak perlu ke database dalam satu kali *request*.
    ```php
    // ❌ BURUK: Memicu N+1 Query (1 query untuk product, dan 12 query tambahan untuk memanggil type & category)
    $products = Product::where('status', 'Aktif')->take(12)->get();
    
    //  BAIK: Hanya memicu 2-3 query gabungan berkat eager loading
    $products = Product::with(['type', 'category'])
        ->where('status', 'Aktif')
        ->take(12)
        ->get();
    ```
*   **Kueri Database Caching**:
    Terapkan caching pada hasil kueri database untuk data yang jarang berubah (seperti data katalog, konfigurasi website, ulasan pelanggan, atau mitra bisnis) menggunakan driver file, Redis, atau Memcached.
    ```php
    $hotDeals = Cache::remember('homepage:hot_deals', now()->addMinutes(30), function () {
        return Type::whereNotNull('image_url')->get();
    });
    ```
*   **Aktifkan PHP OPcache**:
    OPcache menyimpan bytecode skrip PHP yang sudah dikompilasi di memori RAM server, sehingga server tidak perlu memparsing ulang file kode PHP pada setiap request kunjungan.
    *   Aktifkan pada file konfigurasi `php.ini` di server produksi:
        ```ini
        opcache.enable=1
        opcache.memory_consumption=128
        opcache.interned_strings_buffer=8
        opcache.max_accelerated_files=10000
        opcache.revalidate_freq=2
        ```
*   **Gunakan Indexing Database**:
    Pastikan kolom yang sering digunakan di klausa `WHERE` atau `ORDER BY` dalam tabel database Anda memiliki **Index** yang tepat untuk mempercepat pencarian data oleh DBMS.

---

### 3. Aktifkan Pemampatan Teks (Enable Text Compression)
Karena dokumen HTML, file CSS, dan aset JavaScript pada dasarnya adalah file berbasis teks murni dengan banyak karakter berulang (seperti tag HTML, nama kelas CSS), file-file ini sangat ideal untuk dikompresi sebelum dikirimkan lewat jaringan internet.

*   **Gzip**: Algoritma kompresi standar industri yang mampu memotong ukuran teks hingga **70-80%**.
*   **Brotli (br)**: Algoritma kompresi modern yang dikembangkan oleh Google, menawarkan hasil kompresi yang **15-20% lebih padat** dibandingkan Gzip dengan kecepatan dekode yang setara.

#### 🎯 Implementasi Kompresi di Server Apache (`.htaccess`):
Tambahkan blok berikut pada file `.htaccess` di folder `/public` Laravel Anda untuk mengaktifkan Gzip otomatis:

```apache
<IfModule mod_deflate.c>
    # Aktifkan Modul Deflate
    SetOutputFilter DEFLATE
    
    # Kompresi tipe dokumen berbasis teks & kode
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/json
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE image/x-icon
</IfModule>
```

#### 🎯 Memastikan Status Kompresi Aktif:
1. Buka **Google Chrome Developer Tools** (F12) -> Masuk ke tab **Network**.
2. Muat ulang halaman (F5) dan klik pada baris dokumen utama (nama file pertama, biasanya nama domain Anda).
3. Periksa panel **Headers** -> **Response Headers**.
4. Pastikan terdapat header: `Content-Encoding: gzip` atau `Content-Encoding: br`.
