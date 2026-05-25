<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomepageProductController extends Controller
{
    public function index(Request $request)
    {
        $page = (int) $request->input('page', 1);
        $viewport = $request->input('viewport', 'desktop');

        // Tentukan jumlah per halaman berdasarkan viewport
        $perPage = match ($viewport) {
            'mobile' => 6,
            'tablet' => 9,
            default => 12,
        };

        // Batasi hingga maksimal 48 produk untuk halaman utama (4 halaman penuh)
        $maxItems = 48;

        // Ambil versi produk dinamis (Invalidation Hook)
        $version = Cache::rememberForever('homepage_products_version', fn() => time());
        
        // Cache Key unik per halaman, viewport, dan versi produk
        $cacheKey = sprintf('api:v1:homepage:products:v%s:p%d:vp%s', $version, $page, $viewport);

        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($page, $perPage, $maxItems) {
            $totalProducts = Product::query()->where('status', 'Aktif')->count();
            $maxPages = (int) ceil(min($totalProducts, $maxItems) / $perPage);
            
            if ($page > $maxPages && $maxPages > 0) {
                $page = $maxPages;
            }

            $products = Product::query()
                ->with(['type', 'category'])
                ->where('status', 'Aktif')
                ->orderBy('activated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            $mappedProducts = $products->map(function (\App\Models\Product $product) {
                $img = $product->getFirstImageUrl();
                $parsed = parse_url($img);
                if (isset($parsed['host']) && in_array($parsed['host'], ['localhost', '127.0.0.1'])) {
                    $img = ($parsed['path'] ?? '') . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
                }

                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'cat' => $product->type->name ?? $product->category->name ?? 'Produk',
                    'img' => $img,
                    'slug' => $product->getSlug(),
                ];
            })->toArray();

            return [
                'products' => $mappedProducts,
                'page' => $page,
                'maxPages' => $maxPages,
                'total' => min($totalProducts, $maxItems),
            ];
        });

        return response()->json($data)->header('Cache-Control', 'public, max-age=600');
    }
}
