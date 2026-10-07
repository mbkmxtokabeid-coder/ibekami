<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Type;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--domain=https://ibekami.id : Base domain URL for sitemap}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a clean, structured, and SEO-friendly public/sitemap.xml';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $domain = rtrim($this->option('domain') ?: 'https://ibekami.id', '/');
        $today = date('Y-m-d');

        // Mengambil daftar jenis produk (Types), diurutkan sesuai slug
        $types = Type::all()->sortBy(fn($t) => $t->getSlug());

        // Mengambil seluruh produk yang aktif beserta relasi type
        $products = Product::with('type')
            ->where('status', 'Aktif')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Cari tanggal pembaruan terbaru produk
        $latestProductUpdate = $products->first()?->updated_at?->format('Y-m-d') ?: $today;

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml[] = '';

        // ── 1. Halaman Utama ──
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <!-- 1. Halaman Utama (Beranda / Homepage)                  -->';
        $xml[] = '   <!-- Mencakup Hero Banner, Promo, Produk Pilihan & Ulasan   -->';
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <url>';
        $xml[] = "      <loc>{$domain}/</loc>";
        $xml[] = "      <lastmod>{$today}</lastmod>";
        $xml[] = '      <changefreq>daily</changefreq>';
        $xml[] = '      <priority>1.0</priority>';
        $xml[] = '   </url>';
        $xml[] = '';

        // ── 2. Halaman Katalog Utama ──
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <!-- 2. Katalog Lengkap (Semua Produk)                      -->';
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <url>';
        $xml[] = "      <loc>{$domain}/katalog</loc>";
        $xml[] = "      <lastmod>{$latestProductUpdate}</lastmod>";
        $xml[] = '      <changefreq>daily</changefreq>';
        $xml[] = '      <priority>0.9</priority>';
        $xml[] = '   </url>';
        $xml[] = '';

        // ── 3. Halaman Kategori / Jenis Produk ──
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <!-- 3. Kategori & Jenis Layanan Produk                     -->';
        $xml[] = '   <!-- ===================================================== -->';
        foreach ($types as $type) {
            $slug = $type->getSlug();
            $typeName = trim($type->name_id ?: $type->name_en);
            $typeLastmod = $type->updated_at ? $type->updated_at->format('Y-m-d') : $today;

            $xml[] = "   <!-- Kategori: {$typeName} -->";
            $xml[] = '   <url>';
            $xml[] = "      <loc>{$domain}/katalog?type={$slug}</loc>";
            $xml[] = "      <lastmod>{$typeLastmod}</lastmod>";
            $xml[] = '      <changefreq>weekly</changefreq>';
            $xml[] = '      <priority>0.8</priority>';
            $xml[] = '   </url>';
        }
        $xml[] = '';

        // ── 4. Halaman Detail Produk Aktif (Dikelompokkan rapi per Jenis Produk) ──
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <!-- 4. Halaman Detail Produk (Tersusun Rapi per Kategori)  -->';
        $xml[] = '   <!-- ===================================================== -->';

        // Kelompokkan produk berdasarkan jenis produk
        $productsGrouped = $products->groupBy('product_type');

        foreach ($types as $type) {
            $typeProducts = $productsGrouped->get($type->id);
            if ($typeProducts && $typeProducts->count() > 0) {
                $typeName = trim($type->name_id ?: $type->name_en);
                $xml[] = '';
                $xml[] = "   <!-- ── Subkategori Produk: {$typeName} ── -->";

                foreach ($typeProducts as $product) {
                    $slug = $product->getSlug();
                    $productName = trim($product->name_id ?: $product->name_en);
                    $escapedName = htmlspecialchars($productName, ENT_XML1, 'UTF-8');
                    $prodLastmod = $product->updated_at ? $product->updated_at->format('Y-m-d') : $today;

                    $xml[] = "   <!-- Produk: {$escapedName} -->";
                    $xml[] = '   <url>';
                    $xml[] = "      <loc>{$domain}/katalog/{$slug}</loc>";
                    $xml[] = "      <lastmod>{$prodLastmod}</lastmod>";
                    $xml[] = '      <changefreq>weekly</changefreq>';
                    $xml[] = '      <priority>0.8</priority>';
                    $xml[] = '   </url>';
                }
            }
        }

        // Cek jika ada produk tanpa jenis produk
        $uncategorized = $productsGrouped->get('') ?: $productsGrouped->get(null);
        if ($uncategorized && $uncategorized->count() > 0) {
            $xml[] = '';
            $xml[] = '   <!-- ── Produk Lainnya ── -->';
            foreach ($uncategorized as $product) {
                $slug = $product->getSlug();
                $productName = trim($product->name_id ?: $product->name_en);
                $escapedName = htmlspecialchars($productName, ENT_XML1, 'UTF-8');
                $prodLastmod = $product->updated_at ? $product->updated_at->format('Y-m-d') : $today;

                $xml[] = "   <!-- Produk: {$escapedName} -->";
                $xml[] = '   <url>';
                $xml[] = "      <loc>{$domain}/katalog/{$slug}</loc>";
                $xml[] = "      <lastmod>{$prodLastmod}</lastmod>";
                $xml[] = '      <changefreq>weekly</changefreq>';
                $xml[] = '      <priority>0.8</priority>';
                $xml[] = '   </url>';
            }
        }
        $xml[] = '';

        // ── 5. Halaman Fasilitas & Mesin Produksi ──
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <!-- 5. Fasilitas Mesin & Produksi                          -->';
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <url>';
        $xml[] = "      <loc>{$domain}/mesin</loc>";
        $xml[] = "      <lastmod>{$today}</lastmod>";
        $xml[] = '      <changefreq>monthly</changefreq>';
        $xml[] = '      <priority>0.7</priority>';
        $xml[] = '   </url>';
        $xml[] = '';

        // ── 6. Halaman Legalitas & Kebijakan ──
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <!-- 6. Informasi Legalitas & Kebijakan Privasi             -->';
        $xml[] = '   <!-- ===================================================== -->';
        $xml[] = '   <url>';
        $xml[] = "      <loc>{$domain}/privacy-policy</loc>";
        $xml[] = "      <lastmod>{$today}</lastmod>";
        $xml[] = '      <changefreq>yearly</changefreq>';
        $xml[] = '      <priority>0.5</priority>';
        $xml[] = '   </url>';
        $xml[] = '';

        $xml[] = '</urlset>';
        $xml[] = '';

        $output = implode("\n", $xml);
        $filePath = public_path('sitemap.xml');
        file_put_contents($filePath, $output);

        $totalUrls = 1 + 1 + count($types) + count($products) + 1 + 1;
        $this->info("✓ Sitemap berhasil dibuat di: {$filePath}");
        $this->info("✓ Total URL terdaftar: {$totalUrls} URL");

        return Command::SUCCESS;
    }
}
