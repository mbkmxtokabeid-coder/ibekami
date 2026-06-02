<?php
/**
 * IBEKAMI - Native WebP Image Optimization Script
 * 
 * Skrip ini memindai folder-folder aset gambar, mengompresi gambar WebP yang berukuran > 50KB,
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
$maxSize = 50 * 1024;     // 50 KB

// Tahapan optimasi bertingkat untuk memastikan ukuran berkas <= 50 KB
// Setiap tahap mencakup batas dimensi (Max Dimension) dan daftar tingkat kualitas untuk dicoba.
$optimizationStages = [
    ['max_dim' => 1200, 'qualities' => [80, 75, 70, 65, 60, 55, 50, 45, 40, 35, 30]],
    ['max_dim' => 1000, 'qualities' => [70, 65, 60, 55, 50, 45, 40, 35, 30]],
    ['max_dim' => 800,  'qualities' => [70, 65, 60, 55, 50, 45, 40, 35, 30, 25]],
    ['max_dim' => 600,  'qualities' => [70, 60, 50, 40, 30, 20]],
    ['max_dim' => 450,  'qualities' => [65, 55, 45, 35, 25, 15]],
];

echo "===========================================================\n";
echo "🚀 IBEKAMI - MEMULAI OPTIMASI GAMBAR WEBP NATIVE (TARGET MAX 50KB)\n";
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
        $originalImg = @imagecreatefromstring(file_get_contents($file));
        if (!$originalImg) {
            echo "      ❌ Gagal memuat berkas WebP. File mungkin rusak atau tidak kompatibel.\n";
            continue;
        }
        
        // Konversi gambar palette ke truecolor untuk menghindari error imagewebp
        if (!imageistruecolor($originalImg)) {
            imagepalettetotruecolor($originalImg);
        }
        
        $origWidth = imagesx($originalImg);
        $origHeight = imagesy($originalImg);
        
        $tempFile = $file . '.tmp';
        $bestFile = $file . '.best';
        $optimizedSuccess = false;
        $bestSize = $size;
        $bestQualityUsed = 80;
        $bestDimUsed = "{$origWidth}x{$origHeight}";
        
        // Bersihkan berkas best lama jika ada
        if (file_exists($bestFile)) {
            unlink($bestFile);
        }
        
        // Coba setiap tahapan optimasi bertingkat
        foreach ($optimizationStages as $stage) {
            $limit = $stage['max_dim'];
            $qualities = $stage['qualities'];
            
            // Tentukan dimensi baru untuk tahap ini
            $newWidth = $origWidth;
            $newHeight = $origHeight;
            
            if ($origWidth > $limit || $origHeight > $limit) {
                if ($origWidth > $origHeight) {
                    $newWidth = $limit;
                    $newHeight = (int) round(($origHeight / $origWidth) * $limit);
                } else {
                    $newHeight = $limit;
                    $newWidth = (int) round(($origWidth / $origHeight) * $limit);
                }
            }
            
            // Buat gambar truecolor baru untuk resizing/rendering tahap ini
            $workingImg = imagecreatetruecolor($newWidth, $newHeight);
            
            // Pertahankan transparansi kanal alpha untuk WebP
            imagealphablending($workingImg, false);
            imagesavealpha($workingImg, true);
            
            // Copy dan resample gambar dengan resolusi baru
            imagecopyresampled($workingImg, $originalImg, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            
            // Coba setiap tingkat kualitas dalam tahap ini
            foreach ($qualities as $q) {
                if (file_exists($tempFile)) {
                    unlink($tempFile);
                }
                
                @imagewebp($workingImg, $tempFile, $q);
                
                if (file_exists($tempFile)) {
                    $tempSize = filesize($tempFile);
                    
                    // Simpan hasil kompresi terkecil sejauh ini
                    if ($tempSize < $bestSize) {
                        $bestSize = $tempSize;
                        $bestQualityUsed = $q;
                        $bestDimUsed = "{$newWidth}x{$newHeight}";
                        
                        if (file_exists($bestFile)) {
                            unlink($bestFile);
                        }
                        copy($tempFile, $bestFile);
                    }
                    
                    // HENTIKAN proses jika ukuran sudah memenuhi target ideal maksimal (<= 50 KB)
                    if ($tempSize <= $maxSize) {
                        $optimizedSuccess = true;
                        imagedestroy($workingImg);
                        break 2; // Keluar dari loop tingkat kualitas dan loop tahapan
                    }
                }
            }
            
            imagedestroy($workingImg);
        }
        
        imagedestroy($originalImg);
        
        // Bersihkan berkas sementara
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        
        // Terapkan hasil optimasi terbaik
        if (file_exists($bestFile)) {
            $finalSize = filesize($bestFile);
            
            // Jika hasil akhir lebih kecil dari berkas asli, ganti berkas asli
            if ($finalSize < $size) {
                unlink($file);
                rename($bestFile, $file);
                
                $finalKb = round($finalSize / 1024, 1);
                $savedBytes = $size - $finalSize;
                $reduction = round(($savedBytes / $size) * 100, 1);
                
                if ($finalSize <= $maxSize) {
                    echo "      ✅ Sukses kompresi: Dimensi: {$bestDimUsed} | Kualitas: {$bestQualityUsed} | {$originalKb} KB ➡️ {$finalKb} KB (-{$reduction}%)\n";
                } else {
                    echo "      ⚠️  Optimasi Maksimal: Dimensi: {$bestDimUsed} | Kualitas: {$bestQualityUsed} | {$originalKb} KB ➡️ {$finalKb} KB (-{$reduction}%) [Sangat mendekati 50KB]\n";
                }
                
                $totalFilesOptimized++;
                $totalBytesSaved += $savedBytes;
            } else {
                echo "      ⏭️  Hasil kompresi tidak menghasilkan berkas lebih kecil, melewati.\n";
                unlink($bestFile);
            }
        } else {
            echo "      ❌ Gagal mengoptimasi gambar.\n";
        }
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
