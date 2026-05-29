<?php
/**
 * IBEKAMI - Performance Optimization Asset Script
 * 
 * Skrip ini memperkecil logo utama ke 96px secara fisik (untuk skor Lighthouse tinggi),
 * serta membuat versi mobile (480x480px) untuk semua banner agar menghemat data seluler.
 * 
 * Jalankan via terminal: php optimize-performance-assets.php
 */

if (php_sapi_name() !== 'cli') {
    die("Akses ditolak. Skrip ini hanya bisa dijalankan melalui CLI.\n");
}

echo "===========================================================\n";
echo "🚀 IBEKAMI - MEMULAI OPTIMASI LOGO UTAMA & BANNER MOBILE\n";
echo "===========================================================\n\n";

$logoPath = __DIR__ . '/storage/app/public/logos/logo ibekami (3).webp';
$bannerDir = __DIR__ . '/storage/app/public/banners';

$totalOptimized = 0;
$totalBytesSaved = 0;

// 1. OPTIMASI LOGO UTAMA (Ubah ke 96x96px)
if (file_exists($logoPath)) {
    $sizeBefore = filesize($logoPath);
    $img = @imagecreatefromstring(file_get_contents($logoPath));
    if ($img) {
        $width = imagesx($img);
        $height = imagesy($img);
        $targetLogoDim = 96;
        
        echo "🔹 Memproses Logo Utama (logo ibekami (3).webp):\n";
        echo "   Dimensi Awal: {$width}x{$height}px | Ukuran Awal: " . round($sizeBefore/1024, 2) . " KB\n";
        
        if ($width > $targetLogoDim || $height > $targetLogoDim) {
            $resizedImg = imagecreatetruecolor($targetLogoDim, $targetLogoDim);
            
            // Pertahankan transparansi WebP
            imagealphablending($resizedImg, false);
            imagesavealpha($resizedImg, true);
            
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $targetLogoDim, $targetLogoDim, $width, $height);
            imagedestroy($img);
            $img = $resizedImg;
            
            $tempFile = $logoPath . '.tmp';
            @imagewebp($img, $tempFile, 85); // Kualitas 85 sangat jernih untuk logo kecil
            
            if (file_exists($tempFile)) {
                $sizeAfter = filesize($tempFile);
                if ($sizeAfter < $sizeBefore) {
                    unlink($logoPath);
                    rename($tempFile, $logoPath);
                    $totalOptimized++;
                    $totalBytesSaved += ($sizeBefore - $sizeAfter);
                    echo "   ✅ Logo Berhasil Dioptimasi! -> Dimensi: {$targetLogoDim}x{$targetLogoDim}px | Ukuran Baru: " . round($sizeAfter/1024, 2) . " KB (-" . round((($sizeBefore-$sizeAfter)/$sizeBefore)*100, 1) . "%)\n";
                } else {
                    unlink($tempFile);
                    echo "   ℹ️ File terkompresi tidak lebih kecil dari file asli, dilewati.\n";
                }
            }
        } else {
            echo "   ℹ️ Dimensi logo sudah berada di bawah 96px, dilewati.\n";
        }
        imagedestroy($img);
    } else {
        echo "   ❌ Gagal memuat file logo utama.\n";
    }
} else {
    echo "   ⚠️ Logo utama tidak ditemukan di: {$logoPath}\n";
}
echo "\n";

// 2. OPTIMASI BANNER UTAMA (Buat versi mobile 480x480px)
if (is_dir($bannerDir)) {
    echo "🔹 Memproses Gambar-Gambar Banner di /banners:\n";
    
    // Cari file webp yang BUKAN berakhiran _mobile.webp
    $files = glob($bannerDir . '/*.webp');
    $bannersToProcess = array_filter($files, function($file) {
        return strpos($file, '_mobile.webp') === false;
    });

    if (!empty($bannersToProcess)) {
        echo "   Menemukan " . count($bannersToProcess) . " banner utama untuk diperiksa.\n";
        
        foreach ($bannersToProcess as $file) {
            $fileName = basename($file);
            $fileInfo = pathinfo($file);
            $mobileFilePath = $fileInfo['dirname'] . '/' . $fileInfo['filename'] . '_mobile.webp';
            $mobileFileName = basename($mobileFilePath);

            // Cek apakah versi mobile sudah ada
            if (file_exists($mobileFilePath)) {
                echo "   ℹ️ Versi mobile untuk [{$fileName}] sudah ada, dilewati.\n";
                continue;
            }

            $sizeBefore = filesize($file);
            $img = @imagecreatefromstring(file_get_contents($file));
            if (!$img) {
                echo "   ❌ Gagal memuat banner: {$fileName}\n";
                continue;
            }
            
            $width = imagesx($img);
            $height = imagesy($img);
            $targetBannerDim = 480;

            echo "   🔄 Membuat versi mobile untuk [{$fileName}] (dimensi asli {$width}x{$height}px)...\n";
            
            $resizedImg = imagecreatetruecolor($targetBannerDim, $targetBannerDim);
            imagealphablending($resizedImg, false);
            imagesavealpha($resizedImg, true);
            
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $targetBannerDim, $targetBannerDim, $width, $height);
            @imagewebp($resizedImg, $mobileFilePath, 75); // Kualitas 75 sangat optimal untuk mobile
            
            if (file_exists($mobileFilePath)) {
                $sizeAfter = filesize($mobileFilePath);
                $totalOptimized++;
                echo "   ✅ Mobile banner [{$mobileFileName}] berhasil dibuat! Ukuran: " . round($sizeAfter/1024, 2) . " KB (Awal desktop: " . round($sizeBefore/1024, 2) . " KB)\n";
            } else {
                echo "   ❌ Gagal menyimpan mobile banner.\n";
            }
            
            imagedestroy($img);
            imagedestroy($resizedImg);
        }
    } else {
        echo "   ℹ️ Tidak ada banner di folder.\n";
    }
} else {
    echo "   ⚠️ Folder banner tidak ditemukan di: {$bannerDir}\n";
}

echo "\n===========================================================\n";
echo "✨ PROSES OPTIMASI SELESAI!\n";
echo "===========================================================\n";
echo "   Total File Dioptimasi/Dibuat : {$totalOptimized}\n";
echo "   Silakan muat ulang halaman website Anda untuk melihat hasilnya.\n";
echo "===========================================================\n\n";
