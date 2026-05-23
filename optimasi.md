# ↩️ Panduan Optimasi Pengalihan Halaman (Avoid Multiple Page Redirects) - IBEKAMI

Metrik audit Google Lighthouse **"Avoid multiple page redirects"** (Hindari beberapa pengalihan halaman) mendeteksi adanya rantai pengalihan HTTP (seperti 301 atau 302 redirects) sebelum browser dapat menjangkau dokumen HTML tujuan.

Setiap satu pengalihan HTTP memaksa browser melakukan *round-trip* jaringan tambahan untuk membuat permintaan baru ke alamat yang baru. Hal ini menambah latensi ratusan milidetik dan secara langsung memperburuk metrik **TTFB (Time to First Byte)** serta **LCP (Largest Contentful Paint)**.

Dokumen ini memaparkan strategi yang diterapkan pada website **IBEKAMI** untuk mengeliminasi pengalihan ganda dan memastikan pengunjung mendarat di halaman tujuan dalam **satu lompatan tunggal murni**.

---

## 🔗 Dampak Buruk Rantai Pengalihan (Multiple Redirect Chain)

Rantai pengalihan terjadi ketika browser diarahkan secara bertingkat sebelum mendapatkan konten akhir.
*   **Contoh Rantai Buruk (3 Lompatan)**:
    `http://ibekami.id` (301 Redirect) ➔ `https://ibekami.id` (301 Redirect) ➔ `https://www.ibekami.id/`
    *Pengunjung harus menunggu tiga siklus jaringan selesai hanya untuk memuat dokumen awal. Ini memicu penundaan muatan hingga >1 detik pada jaringan seluler lambat.*
*   **Contoh Rantai Optimal (1 Lompatan Maksimal)**:
    `http://ibekami.id` (301 Direct Redirect) ➔ `https://ibekami.id/` (Tujuan Akhir Canonical).

---

## 🛠️ Strategi Optimasi Pengalihan Halaman pada IBEKAMI

Kita menerapkan 4 taktik utama untuk memastikan nol rantai pengalihan ganda pada website IBEKAMI:

### 1. Konfigurasi Pengalihan Satu Lompatan di `.htaccess`
Kita menyusun ulang aturan penulisan ulang (*Rewrite Rules*) pada berkas server Apache Hostinger [public/.htaccess](file:///C:/Users/USER/.gemini/antigravity/worktrees/ibekami_bckend/open-project-workspace/public/.htaccess) untuk menggabungkan pemaksaan HTTPS dan pemaksaan URL tanpa WWW (*non-WWW*) dalam satu baris aturan kondisional tunggal.
*   **Aturan yang Diterapkan**:
    Jika pengunjung mengakses via `http://` atau `www.ibekami.id`, server Apache akan mendeteksinya secara bersamaan dan mengarahkannya langsung ke `https://ibekami.id/` dalam **satu respons 301 saja**, memotong rantai pengalihan menengah.

---

### 2. Tautan Berbasis Protokol Aman & Kunci Kanonikal
Seluruh tautan internal aplikasi di dalam file Blade diatur untuk menggunakan alamat aman penuh:
*   **Tindakan pada IBEKAMI**:
    *   Menggunakan fungsi pembantu Laravel `route()` atau `secure_url()` untuk menghasilkan tautan yang selalu berprotokol `https://` secara otomatis.
    *   Mendaftarkan elemen `<link rel="canonical" href="...">` yang dinamis di `<head>` untuk menegaskan URL utama kepada mesin pencari (SEO), mencegah Google melakukan perayapan ganda atau mengindeks URL pengalihan.

---

### 3. SPA Mode Tanpa Pengalihan via `wire:navigate`
Setiap perpindahan halaman konvensional sering kali memicu pengalihan rute di sisi server jika terdapat perubahan bahasa atau session.
*   **Tindakan pada IBEKAMI**:
    *   Dengan menyematkan Livewire SPA Navigation (`wire:navigate`), perpindahan halaman diproses di sisi klien menggunakan Javascript Fetch.
    *   Browser mengambil konten halaman tujuan dan langsung menimpa DOM tubuh (*body*) halaman tanpa memicu siklus HTTP redirect penuh dari server.

---

### 4. Optimalisasi Jalur Pengalihan Rute Laravel (Language Switcher)
Saat pengunjung mengganti bahasa di switcher bahasa:
*   **Sebelumnya**: `/lang/{locale}` ➔ Memproses Session ➔ Mengalihkan ke halaman sebelumnya (sering kali memicu redirect berantai jika halaman sebelumnya memiliki rute redirect tersendiri).
*   **Solusi pada IBEKAMI**:
    *   Di dalam `LanguageController`, rute balik dideteksi secara ketat. Jika URL asal merupakan rute redirect, sistem langsung mengarahkannya ke URL kanonikal aslinya dalam satu respons pengalihan 302 terarah.

---

## 🧪 Lembar Verifikasi Jalur Pengalihan

Gunakan alat bantu CLI di bawah ini untuk memverifikasi rantai pengalihan pada website live:

1.  **Pengujian Melalui Curl**:
    Jalankan perintah berikut pada terminal untuk memeriksa rantai redirect `http` menuju `https`:
    ```bash
    curl -Iv http://ibekami.id
    ```
    *Pastikan header `Location` langsung merujuk ke `https://ibekami.id/` (bukan dialihkan kembali ke www).*
2.  **Audit Lighthouse Performance**:
    *   Jalankan audit Lighthouse di Chrome DevTools.
    *   Pastikan tidak ada peringatan di bawah kategori **"Avoid multiple page redirects"**.
