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
        
        // Disable PWA-related browser features
        // Feature-Policy is deprecated, Permissions-Policy is the modern standard
        $response->headers->set('Permissions-Policy', 'display-capture=(), web-share=()');
        
        // Security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Special handling for manifest.json
        if ($request->is('manifest.json')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }
        
        return $response;
    }
}
