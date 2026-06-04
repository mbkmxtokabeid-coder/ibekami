<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RenameProductImagesToSeo extends Command
{
    protected $signature = 'products:rename-images-seo {--dry-run : Menjalankan simulasi tanpa mengubah data asli}';
    protected $description = 'Mengubah nama file gambar produk yang sudah ada menjadi ramah SEO secara massal';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->info('--- MENJALANKAN SIMULASI (DRY RUN) ---');
        }

        $products = Product::all();
        $totalProducts = $products->count();
        $processed = 0;
        $renamed = 0;
        $failed = 0;

        $this->info("Menemukan {$totalProducts} produk untuk diproses.");

        foreach ($products as $product) {
            $images = $product->image_url;
            if (empty($images) || !is_array($images)) {
                continue;
            }

            $productId = $product->product_id;
            $slug = Str::slug($product->name_id ?: $product->name_en ?: 'product');
            $updatedImages = [];
            $hasChanges = false;

            foreach ($images as $i => $oldFilename) {
                // Generate target SEO filename
                $newFilename = $this->getUniqueSeoFilename($slug, $i, $productId, $oldFilename, $updatedImages);

                if ($oldFilename !== $newFilename) {
                    $oldPath = 'products/' . $oldFilename;
                    $newPath = 'products/' . $newFilename;

                    if (Storage::disk('public')->exists($oldPath)) {
                        if (!$dryRun) {
                            try {
                                Storage::disk('public')->move($oldPath, $newPath);
                                Cache::forget('prod_img_url:' . md5($oldFilename));
                                Cache::forget('prod_img_url:' . md5($newFilename));
                                $this->line("Mengubah nama file: {$oldFilename} -> {$newFilename}");
                            } catch (\Exception $e) {
                                $this->error("Gagal memindahkan file {$oldFilename}: " . $e->getMessage());
                                Log::error("Artisan rename failed for product {$productId}: " . $e->getMessage());
                                $newFilename = $oldFilename; // Fallback
                                $failed++;
                            }
                        } else {
                            $this->line("[Simulasi] Akan mengubah nama file: {$oldFilename} -> {$newFilename}");
                        }
                    } else {
                        // Check in alternative directory if exists (fallback path)
                        $altOldPath = 'gambar_produk/' . $oldFilename;
                        if (Storage::disk('public')->exists($altOldPath)) {
                            if (!$dryRun) {
                                try {
                                    Storage::disk('public')->move($altOldPath, $newPath);
                                    Cache::forget('prod_img_url:' . md5($oldFilename));
                                    Cache::forget('prod_img_url:' . md5($newFilename));
                                    $this->line("Mengubah nama file (alt path): {$oldFilename} -> {$newFilename}");
                                } catch (\Exception $e) {
                                    $this->error("Gagal memindahkan file {$oldFilename} dari path alternatif: " . $e->getMessage());
                                    $newFilename = $oldFilename; // Fallback
                                    $failed++;
                                }
                            } else {
                                $this->line("[Simulasi] Akan mengubah nama file dari path alternatif: {$oldFilename} -> {$newFilename}");
                            }
                        } else {
                            $this->warn("File fisik tidak ditemukan: {$oldFilename} untuk produk '{$product->name_id}'");
                            // Keep filename anyway to avoid losing database reference
                            $newFilename = $oldFilename;
                        }
                    }

                    $updatedImages[] = $newFilename;
                    $hasChanges = true;
                    $renamed++;
                } else {
                    $updatedImages[] = $oldFilename;
                }
            }

            if ($hasChanges && !$dryRun) {
                $product->update(['image_url' => $updatedImages]);
            }

            $processed++;
        }

        $this->info("Proses selesai!");
        $this->info("Total produk diproses: {$processed}");
        $this->info("Total gambar diubah namanya: {$renamed}");
        if ($failed > 0) {
            $this->error("Total kegagalan: {$failed}");
        }
    }

    private function generateSeoFilename(string $slug, int $index, string $productId): string
    {
        $patterns = [
            "cetak-{$slug}-terdekat-di-medan",
            "cetak-{$slug}-satuan-di-medan",
            "cetak-{$slug}-express-di-medan",
            "cetak-{$slug}-grosiran-di-medan",
            "cetak-{$slug}-partai-besar-di-medan",
            "cetak-{$slug}-partai-kecil-di-medan",
            "cetak-{$slug}-custom-di-medan",
            "tempat-cetak-{$slug}-di-medan",
            "jasa-cetak-{$slug}-di-medan",
            "percetakan-{$slug}-di-medan"
        ];

        $totalPatterns = count($patterns);
        $startIndex = abs(crc32($productId)) % $totalPatterns;
        $patternIndex = ($startIndex + $index) % $totalPatterns;
        
        return $patterns[$patternIndex];
    }

    private function getUniqueSeoFilename(string $slug, int $index, string $productId, string $currentFilename, array $newlyAssignedFilenames): string
    {
        $baseName = $this->generateSeoFilename($slug, $index, $productId);
        $filename = $baseName . '.webp';

        if ($currentFilename === $filename) {
            return $filename;
        }

        // Avoid collision with already renamed images in the current batch run
        $counter = 1;
        while (
            Storage::disk('public')->exists('products/' . $filename) || 
            in_array($filename, $newlyAssignedFilenames)
        ) {
            $filename = $baseName . '-' . $counter . '.webp';
            if ($currentFilename === $filename) {
                return $filename;
            }
            $counter++;
        }

        return $filename;
    }
}
