<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Abaikan jika aplikasi sedang maintenance atau extension zlib tidak aktif
        if (app()->isDownForMaintenance() || !extension_loaded('zlib')) {
            return $response;
        }

        // Cek apakah browser mendukung gzip
        $acceptEncoding = $request->header('Accept-Encoding', '');
        if (!str_contains($acceptEncoding, 'gzip')) {
            return $response;
        }

        // Dapatkan konten respons
        $content = $response->getContent();
        if (empty($content) || !is_string($content)) {
            return $response;
        }

        // Tentukan apakah Content-Type dari respons dapat/layak dikompresi
        $contentType = $response->headers->get('Content-Type', '');
        $compressibleTypes = [
            'text/html',
            'text/plain',
            'text/css',
            'application/javascript',
            'application/json',
            'application/xml',
            'text/xml',
        ];

        $isCompressible = false;
        foreach ($compressibleTypes as $type) {
            if (str_contains(strtolower($contentType), $type)) {
                $isCompressible = true;
                break;
            }
        }

        if (!$isCompressible) {
            return $response;
        }

        // Jangan kompresi ulang jika respons sudah memiliki encoding kompresi lain
        if ($response->headers->has('Content-Encoding')) {
            return $response;
        }

        // Kompresi konten menggunakan gzip (tingkat kompresi 5 untuk keseimbangan CPU & rasio kompresi)
        $compressed = gzencode($content, 5);
        
        if ($compressed !== false) {
            $response->setContent($compressed);
            $response->headers->set('Content-Encoding', 'gzip');
            $response->headers->set('Vary', 'Accept-Encoding');
            
            // Perbarui header Content-Length
            if ($response->headers->has('Content-Length')) {
                $response->headers->set('Content-Length', strlen($compressed));
            }
        }

        return $response;
    }
}
