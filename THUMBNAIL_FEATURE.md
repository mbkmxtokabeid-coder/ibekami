# Fitur Thumbnail Banner untuk Optimasi LCP

## 📋 Overview

Fitur ini menambahkan kemampuan untuk mengupload **thumbnail** pada banner video. Thumbnail akan ditampilkan terlebih dahulu sebelum video dimuat, sehingga meningkatkan **LCP (Largest Contentful Paint)** score dan user experience.

## 🎯 Manfaat

1. **LCP Score Lebih Baik**: Thumbnail ringan (<100KB) dimuat lebih cepat daripada video (beberapa MB)
2. **User Experience**: Pengguna langsung melihat konten visual tanpa menunggu video
3. **Bandwidth Efisien**: Video hanya dimuat setelah halaman selesai loading
4. **SEO Friendly**: Google merekomendasikan gambar statis untuk hero banner

## 🔧 Cara Menggunakan

### 1. Upload Banner dengan Thumbnail

Di halaman **Admin > Backend > Banner List**:

1. Klik **"+ Tambah Banner"** atau **Edit** banner yang ada
2. Upload **Video** (mp4/mov/avi/mkv/webm) - maks 100MB
3. Upload **Thumbnail** (jpg/png/webp) - maks 2MB
4. Klik **"Simpan Banner"**

### 2. Ekstrak Thumbnail dari Video (Recommended)

Gunakan FFmpeg untuk mengekstrak frame pertama video sebagai thumbnail:

```bash
# Ekstrak frame pertama sebagai WebP
ffmpeg -i video.mp4 -vframes 1 thumbnail.webp

# Atau ekstrak frame pada detik tertentu (contoh: detik ke-2)
ffmpeg -ss 00:00:02 -i video.mp4 -vframes 1 thumbnail.webp
```

### 3. Kompres Thumbnail (Opsional)

Jika ukuran thumbnail masih besar, kompres menggunakan:

- **Online**: [Squoosh.app](https://squoosh.app)
- **CLI**: `cwebp -q 80 thumbnail.png -o thumbnail.webp`

Target ukuran: **<100KB** (sistem akan otomatis kompres saat upload)

## 🏗️ Implementasi di Frontend

Gunakan struktur HTML berikut di halaman utama:

```blade
<div wire:ignore
     x-data="{ 
         videoLoaded: false,
         videoSrc: '',
         init() {
             window.addEventListener('load', () => {
                 this.videoSrc = '{{ Storage::url($banner->media_url) }}';
             });
         }
     }"
     class="relative w-full min-h-[500px] overflow-hidden bg-gray-100">
    
    {{-- Thumbnail (LCP Element) --}}
    <img x-show="!videoLoaded"
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         src="{{ Storage::url($banner->thumbnail_url) }}" 
         width="1920" 
         height="1080" 
         fetchpriority="high" 
         loading="eager" 
         alt="Banner Thumbnail" 
         class="absolute top-0 left-0 z-10 w-full h-full object-cover">
    
    {{-- Video (Lazy Loaded) --}}
    <template x-if="videoSrc">
        <video autoplay muted loop playsinline
               :src="videoSrc"
               @canplaythrough="videoLoaded = true"
               class="absolute top-0 left-0 z-20 w-full h-full object-cover">
        </video>
    </template>
</div>
```

## 📊 Hasil yang Diharapkan

### Sebelum (Tanpa Thumbnail)
- LCP: **+5-7 detik** (menunggu video download)
- User melihat: Loading blank/spinner

### Sesudah (Dengan Thumbnail)
- LCP: **<1 detik** (thumbnail <100KB)
- User melihat: Gambar langsung muncul
- Video: Dimuat di background setelah page load

## 🗄️ Database Schema

Kolom baru ditambahkan ke tabel `banners`:

```sql
ALTER TABLE banners ADD COLUMN thumbnail_url VARCHAR(255) NULL AFTER media_type;
```

## 📁 File yang Dimodifikasi

1. **Migration**: `database/migrations/2026_05_20_045050_add_thumbnail_to_banners_table.php`
2. **Model**: `app/Models/Banner.php`
3. **Component**: `app/Livewire/Admin/Backend/BannerList.php`
4. **View**: `resources/views/livewire/admin/backend/banner-list.blade.php`
5. **Config**: `config/livewire.php` (added 'webm' to preview_mimes)

## 🎨 Fitur Kompresi Otomatis

- **Thumbnail**: Otomatis dikompres ke WebP dengan target <100KB
- **Video**: Otomatis dikompres ke WebM (jika FFmpeg tersedia)
- **Gambar Banner**: Otomatis dikompres ke WebP dengan target 20-50KB

## 💡 Tips & Best Practices

1. **Gunakan frame terbaik**: Pilih frame yang paling mewakili brand/produk
2. **Resolusi optimal**: 1920x1080px atau sesuai rasio video
3. **Ukuran file**: Usahakan <50KB untuk LCP terbaik
4. **Format**: WebP lebih baik dari JPEG/PNG (30-50% lebih kecil)
5. **Alt text**: Tambahkan deskripsi yang jelas untuk SEO

## 🔍 Troubleshooting

### Thumbnail tidak muncul
- Pastikan file sudah terupload (cek di `storage/app/public/banners/`)
- Jalankan `php artisan storage:link` jika belum
- Cek permission folder storage

### Ukuran file terlalu besar
- Kompres manual dengan Squoosh.app sebelum upload
- Atau biarkan sistem kompres otomatis (target <100KB)

### Video tidak autoplay
- Pastikan attribute `muted` ada (browser policy)
- Gunakan `playsinline` untuk mobile
- Load video setelah `window.load` event

## 📚 Referensi

- [Google Web Vitals - LCP](https://web.dev/lcp/)
- [FFmpeg Documentation](https://ffmpeg.org/documentation.html)
- [WebP Image Format](https://developers.google.com/speed/webp)
- [Lazy Loading Video](https://web.dev/lazy-loading-video/)

---

**Created**: May 20, 2026  
**Version**: 1.0.0  
**Author**: Kiro AI Assistant
