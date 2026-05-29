<?php
/**
 * IBEKAMI - Brand Logo & Partner Logos Resize & Optimization Script
 * 
 * Skrip ini khusus memperkecil dimensi logo utama dan gambar mitra (partner) 
 * ke resolusi yang sesuai dengan tampilannya di layar (120px untuk logo, 180px untuk partner).
 * Hal ini mengeliminasi pemborosan data hingga 95%+ dan mendongkrak skor performa mobile.
 * 
 * Jalankan via terminal: php resize-logos-partners-webp.php
 */

if (php_sapi_name() !== 'cli') {
    die("Akses ditolak. Skrip ini hanya bisa dijalankan melalui CLI.\n");
}

echo "===========================================================\n";
echo "🚀 IBEKAMI - MEMULAI OPTIMASI LOGO & GAMBAR PARTNER\n";
echo "===========================================================\n\n";

$logoPath = __DIR__ . '/storage/app/public/logos/logo ibekami (3).webp';
$partnerDir = __DIR__ . '/storage/app/public/gambar_partner';

$totalOptimized = 0;
$totalBytesSaved = 0;

// 1. OPTIMASI LOGO UTAMA (Ubah ke maks 120px)
if (file_exists($logoPath)) {
    $sizeBefore = filesize($logoPath);
    $img = @imagecreatefromstring(file_get_contents($logoPath));
    if ($img) {
        $width = imagesx($img);
        $height = imagesy($img);
        $maxLogoDim = 120;
        
        echo "🔹 Memproses Logo Utama (logo ibekami (3).webp):\n";
        echo "   Dimensi Awal: {$width}x{$height}px | Ukuran Awal: " . round($sizeBefore/1024, 1) . " KB\n";
        
        if ($width > $maxLogoDim || $height > $maxLogoDim) {
            if ($width > $height) {
                $newWidth = $maxLogoDim;
                $newHeight = (int) round(($height / $width) * $maxLogoDim);
            } else {
                $newHeight = $maxLogoDim;
                $newWidth = (int) round(($width / $height) * $maxLogoDim);
            }
            
            $resizedImg = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resizedImg, false);
            imagesavealpha($resizedImg, true);
            
            imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($img);
            $img = $resizedImg;
            
            $tempFile = $logoPath . '.tmp';
            imagealphablending($img, false);
            imagesavealpha($img, true);
            @imagewebp($img, $tempFile, 80);
            
            if (file_exists($tempFile)) {
                $sizeAfter = filesize($tempFile);
                if ($sizeAfter < $sizeBefore) {
                    unlink($logoPath);
                    rename($tempFile, $logoPath);
                    $totalOptimized++;
                    $totalBytesSaved += ($sizeBefore - $sizeAfter);
                    echo "   ✅ Logo Berhasil Dioptimasi! -> Dimensi: {$newWidth}x{$newHeight}px | Ukuran: " . round($sizeAfter/1024, 1) . " KB (-" . round((($sizeBefore-$sizeAfter)/$sizeBefore)*100, 1) . "%)\n";
                } else {
                    unlink($tempFile);
                    echo "   ℹ️ File terkompresi tidak lebih kecil dari file asli, melewati.\n";
                }
            }
        } else {
            echo "   ℹ️ Dimensi logo sudah berada di bawah 120px, dilewati.\n";
        }
        imagedestroy($img);
    } else {
        echo "   ❌ Gagal memuat file logo utama.\n";
    }
} else {
    echo "   ⚠️ Logo utama tidak ditemukan di: {$logoPath}\n";
}
echo "\n";

// 2. OPTIMASI GAMBAR PARTNER (Ubah ke maks 180px)
if (is_dir($partnerDir)) {
    echo "🔹 Memproses Gambar-Gambar Partner di /gambar_partner:\n";
    $files = glob($partnerDir . '/*.webp');
    if (!empty($files)) {
        echo "   Menemukan " . count($files) . " gambar partner.\n";
        
        foreach ($files as $file) {
            $sizeBefore = filesize($file);
            $img = @imagecreatefromstring(file_get_contents($file));
            if (!$img) {
                echo "   ❌ Gagal memuat gambar partner: " . basename($file) . "\n";
                continue;
            }
            
            $width = imagesx($img);
            $height = imagesy($img);
            $maxPartnerDim = 180;
            $fileName = basename($file);
            
            if ($width > $maxPartnerDim || $height > $maxPartnerDim) {
                if ($width > $height) {
                    $newWidth = $maxPartnerDim;
                    $newHeight = (int) round(($height / $width) * $maxPartnerDim);
                } else {
                    $newHeight = $maxPartnerDim;
                    $newWidth = (int) round(($width / $height) * $maxPartnerDim);
                }
                
                $resizedImg = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resizedImg, false);
                imagesavealpha($resizedImg, true);
                
                imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($img);
                $img = $resizedImg;
                
                $tempFile = $file . '.tmp';
                imagealphablending($img, false);
                imagesavealpha($img, true);
                @imagewebp($img, $tempFile, 75); // Kualitas 75 sangat memadai untuk logo partner kecil
                
                if (file_exists($tempFile)) {
                    $sizeAfter = filesize($tempFile);
                    if ($sizeAfter < $sizeBefore) {
                        unlink($file);
                        rename($tempFile, $file);
                        $totalOptimized++;
                        $totalBytesSaved += ($sizeBefore - $sizeAfter);
                        echo "   ✅ Partner [{$fileName}] dioptimasi! Dimensi: {$width}x{$height}px ➡️ {$newWidth}x{$newHeight}px | Ukuran: " . round($sizeBefore/1024, 1) . " KB ➡️ " . round($sizeAfter/1024, 1) . " KB (-" . round((($sizeBefore-$sizeAfter)/$sizeBefore)*100, 1) . "%)\n";
                    } else {
                        unlink($tempFile);
                    }
                }
            } else {
                // Jika dimensi sudah kecil, re-compress jika ukurannya di atas 20KB
                if ($sizeBefore > 20 * 1024) {
                    $tempFile = $file . '.tmp';
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                    @imagewebp($img, $tempFile, 70);
                    
                    if (file_exists($tempFile)) {
                        $sizeAfter = filesize($tempFile);
                        if ($sizeAfter < $sizeBefore) {
                            unlink($file);
                            rename($tempFile, $file);
                            $totalOptimized++;
                            $totalBytesSaved += ($sizeBefore - $sizeAfter);
                            echo "   ✅ Partner [{$fileName}] di-kompresi ulang! " . round($sizeBefore/1024, 1) . " KB ➡️ " . round($sizeAfter/1024, 1) . " KB\n";
                        } else {
                            unlink($tempFile);
                        }
                    }
                }
            }
            imagedestroy($img);
        }
    } else {
        echo "   ℹ️ Tidak ada gambar partner di folder.\n";
    }
} else {
    echo "   ⚠️ Folder partner tidak ditemukan di: {$partnerDir}\n";
}

$savedMb = round($totalBytesSaved / (1024 * 1024), 2);
echo "\n===========================================================\n";
echo "✨ OPTIMASI SELESAI!\n";
echo "===========================================================\n";
echo "   Total Gambar Dioptimasi : {$totalOptimized}\n";
echo "   Total Ruang Dihemat     : {$savedMb} MB\n";
echo "===========================================================\n\n";
