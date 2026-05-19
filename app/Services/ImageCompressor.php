<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageCompressor
{
    /**
     * Target file size range in bytes
     */
    private const MIN_SIZE = 20 * 1024; // 20 KB
    private const MAX_SIZE = 50 * 1024; // 50 KB

    /**
     * Compress and convert an image to WebP format with target size 100-300KB.
     *
     * @param  string  $sourcePath   Absolute path to the uploaded temp file
     * @param  string  $storagePath  Relative path inside storage/app/public (e.g. products/abc.webp)
     * @return string  The storage-relative path of the output file
     */
    public function compressToWebP(string $sourcePath, string $storagePath): string
    {
        // Ensure output directory exists
        $outputAbsPath = Storage::disk('public')->path($storagePath);
        $dir = \dirname($outputAbsPath);
        if (!\is_dir($dir)) {
            \mkdir($dir, 0755, true);
        }

        // Load image with Intervention Image
        $image = Image::read($sourcePath);

        // Get original dimensions
        $originalWidth  = $image->width();
        $originalHeight = $image->height();

        // Calculate target dimensions (maintain aspect ratio, max 800px for smaller file size)
        $maxDimension = 800;
        if ($originalWidth > $maxDimension || $originalHeight > $maxDimension) {
            if ($originalWidth > $originalHeight) {
                $image->scale(width: $maxDimension);
            } else {
                $image->scale(height: $maxDimension);
            }
        }

        // Try different quality levels to achieve target file size
        $quality = 70; // Start with medium quality for aggressive compression
        $attempts = 0;
        $maxAttempts = 20; // More attempts for very tight target
        $tempPath = $outputAbsPath . '.tmp';

        do {
            // Encode to WebP with current quality
            $encoded = $image->toWebp($quality);
            \file_put_contents($tempPath, $encoded);
            $fileSize = \filesize($tempPath);

            // Check if file size is within target range
            if ($fileSize >= self::MIN_SIZE && $fileSize <= self::MAX_SIZE) {
                break;
            }

            // Adjust quality based on file size
            if ($fileSize > self::MAX_SIZE) {
                // File too large, reduce quality aggressively
                $quality -= 5;
            } elseif ($fileSize < self::MIN_SIZE && $quality < 85) {
                // File too small, increase quality slightly
                $quality += 2;
            } else {
                // File is smaller than MIN_SIZE but quality is already high
                // Accept it as is
                break;
            }

            $attempts++;
        } while ($attempts < $maxAttempts && $quality > 10 && $quality <= 90);

        // Move temp file to final destination
        \rename($tempPath, $outputAbsPath);

        // Log compression result
        $finalSize = \filesize($outputAbsPath);
        Log::info("Image compressed: {$storagePath}", [
            'original_size' => \filesize($sourcePath),
            'final_size'    => $finalSize,
            'quality'       => $quality,
            'dimensions'    => $image->width() . 'x' . $image->height(),
        ]);

        return $storagePath;
    }

    /**
     * Check if Intervention Image is available.
     */
    public static function isAvailable(): bool
    {
        return class_exists(\Intervention\Image\Laravel\Facades\Image::class);
    }
}
