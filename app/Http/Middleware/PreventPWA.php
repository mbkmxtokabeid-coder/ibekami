<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prevent PWA Install Prompt Middleware
 * 
 * This middleware adds HTTP headers to prevent browsers from showing
 * the "Install App" or "Add to Home Screen" prompt.
 * 
 * Critical for preventing Android WebAPK install prompts that confuse users.
 * 
 * @see PWA_INSTALL_PROMPT_FIX.md for full documentation
 */
class PreventPWA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Blokir semua fitur yang bisa memicu PWA install prompt atau permission popup
        // web-share: mencegah popup "wants to access other apps and services"
        // display-capture, fullscreen, picture-in-picture: fitur yang tidak dipakai
        $response->headers->set(
            'Permissions-Policy',
            'display-capture=(), web-share=(), fullscreen=(), picture-in-picture=()'
        );
        
        // Security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Blokir akses ke file PWA — return 404 jika ada yang request langsung
        // KECUALI sw.js yang merupakan kill switch untuk unregister SW lama
        if ($request->is('manifest.json') || $request->is('service-worker.js') || $request->is('offline.html')) {
            abort(404);
        }
        
        return $response;
    }
}
