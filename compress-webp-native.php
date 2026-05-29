<?php
/**
 * IBEKAMI - Native WebP Image Optimization Script
 * 
 * Skrip ini memindai folder-folder aset gambar, mengompresi gambar WebP yang berukuran > 250KB,
 * serta memperkecil dimensi gambar jika melebihi 1200px guna meningkatkan skor LCP secara maksimal.
 * 
 * Jalankan via terminal: php compress-webp-native.php
 */

// Batasi akses eksekusi hanya lewat CLI demi keamanan
if (php_sapi_name() !== 'cli') {
    die("Akses ditolak. Skrip ini hanya bisa dijalankan melalui Command Line Interface (CLI).\n");
}

$directories = [
    __DIR__ . '/storage/app/public/gambar_produk',
    __DIR__ . '/storage/app/public/products',
    __DIR__ . '/storage/app/public/banners',
    __DIR__ . '/storage/app/public/banner_picture',
    __DIR__ . '/storage/app/public/gambar_partner',
    __DIR__ . '/storage/app/public/machine_picture',
    __DIR__ . '/storage/app/public/gambar_jenis',
    __DIR__ . '/storage/app/public/types',
];

// Target ukuran maksimum dalam bytes
$maxSize = 100 * 1024;    // 100 KB
$targetMin = 50 * 1024;   // 50 KB
$targetMax = 150 * 1024;  // 150 KB
$maxDimension = 1200;     // Maksimum lebar/tinggi piksel untuk di atas viewport

echo "===========================================================\n";
echo "🚀 IBEKAMI - MEMULAI OPTIMASI GAMBAR WEBP NATIVE\n";
echo "===========================================================\n\n";

$totalFilesFound = 0;
$totalFilesOptimized = 0;
$totalBytesSaved = 0;

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        echo "⚠️  Folder tidak ditemukan, melewati: {$dir}\n";
        continue;
    }
    
    $folderName = basename($dir);
    echo "📁 Memproses folder: {$folderName}\n";
    
    $files = glob($dir . '/*.webp');
    if (empty($files)) {
        echo "   ℹ️  Tidak ada gambar WebP di folder ini.\n\n";
        continue;
    }
    
    echo "   Ditemukan " . count($files) . " berkas WebP\n";
    
    foreach ($files as $file) {
        $totalFilesFound++;
        $size = filesize($file);
        
        // Lewati jika berkas sudah berada di bawah target ukuran maks
        if ($size <= $maxSize) {
            continue;
        }
        
        $originalKb = round($size / 1024, 1);
        $fileName = basename($file);
        
        echo "   🔍 Menemukan gambar WebP besar: {$fileName} ({$originalKb} KB)\n";
        
        // Muat gambar secara native menggunakan GD Library secara aman (imagecreatefromstring menangani format webp lossless lebih baik di Windows)
        $img = @imagecreatefromstring(file_get_contents($file));
        if (!$img) {
            echo "      ❌ Gagal memuat berkas WebP. File mungkin rusak atau tidak kompatibel.\n";
            continue;
        }
        
        $width = imagesx($img);
        $height = imagesy($img);
        $resized = false;
        
        // Lakukan resizing jika resolusi gambar terlampau tinggi
        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width > $height) {
                $newWidth = $maxDimension;
                $newHeight = (int) round(($height / $width) * $maxDimension);
            } else {
                $newHeight = $maxDimension;
                $newWidth = (int) round(($width / $height) * $maxDimension);
            }
            
            echo "      📏 Mengubah dimensi: {$width}x{$height}px ➡️ {$newWidth}x{$newHeight}px\n";
            
            $resizedImg = imagecreatetruecolor($newWidth, $newHeight);
            
            // Pertahankan transparansi kanal alpha untuk WebP
            imagealphablending($resizedImg, false);
            imagesavealpha($resizedImg, true);
            
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($img);
            $img = $resizedImg;
            $resized = true;
        }
        
        // Mulai kompresi dengan menurunkan kualitas secara iteratif hingga mencapai target ukuran
        $quality = 80;
        $tempFile = $file . '.tmp';
        $optimizedSuccess = false;
        
        while ($quality >= 40) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            // Konfigurasi transparansi sebelum menyimpan
            imagealphablending($img, false);
            imagesavealpha($img, true);
            
            @imagewebp($img, $tempFile, $quality);
            
            if (!file_exists($tempFile)) {
                break;
            }
            
            $tempSize = filesize($tempFile);
            
            // Berhenti jika ukuran berkas sudah memenuhi target ideal maksimal
            if ($tempSize <= $targetMax) {
                $optimizedSuccess = true;
                break;
            }
            
            $quality -= 5;
        }
        
        if (file_exists($tempFile)) {
            $finalSize = filesize($tempFile);
            
            // Jika hasil akhir lebih kecil dari berkas asli, ganti berkas asli
            if ($finalSize < $size) {
                unlink($file);
                rename($tempFile, $file);
                
                $finalKb = round($finalSize / 1024, 1);
                $savedBytes = $size - $finalSize;
                $reduction = round(($savedBytes / $size) * 100, 1);
                
                echo "      ✅ Sukses kompresi: Kualitas: {$quality} | {$originalKb} KB ➡️ {$finalKb} KB (-{$reduction}%)\n";
                
                $totalFilesOptimized++;
                $totalBytesSaved += $savedBytes;
            } else {
                echo "      ⚠️  Hasil kompresi tidak menghasilkan berkas lebih kecil, melewati.\n";
                unlink($tempFile);
            }
        }
        
        imagedestroy($img);
    }
    echo "\n";
}

$savedMb = round($totalBytesSaved / (1024 * 1024), 2);
echo "===========================================================\n";
echo "✨ OPTIMASI SELESAI!\n";
echo "===========================================================\n";
echo "   Total Berkas Diperiksa  : {$totalFilesFound}\n";
echo "   Total Berkas Dioptimasi : {$totalFilesOptimized}\n";
echo "   Total Ruang Dihemat     : {$savedMb} MB\n";
echo "===========================================================\n\n";
