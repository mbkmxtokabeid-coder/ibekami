<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Switch application language
     * 
     * Rate Limited: 10 requests per minute per IP
     * Protects against session flooding and log pollution
     */
    public function switch(Request $request, string $locale)
    {
        // Validate locale
        if (!in_array($locale, ['id', 'en'])) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid locale. Only "id" and "en" are supported.',
                ], 400);
            }
            return redirect()->route('home');
        }

        // Store locale in session
        $request->session()->put('locale', $locale);
        
        // Set application locale
        app()->setLocale($locale);

        Log::info('Language switched', [
            'locale' => $locale,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        $cookie = cookie()->forever('locale', $locale);

        // If AJAX / JSON request
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => 'Language switched successfully.',
            ])->withCookie($cookie);
        }

        // For non-AJAX direct requests, perform a safe, single-hop canonical redirect
        $backUrl = url()->previous();
        
        // Sanitize target URL to ensure it is secure, canonical, and not causing infinite redirect loops
        if (empty($backUrl) || !str_starts_with($backUrl, url('/')) || str_contains($backUrl, '/lang/')) {
            $backUrl = route('home');
        } else {
            // Standardize URL to secure HTTPS (or HTTP on local) and strip out WWW prefix
            $parsedUrl = parse_url($backUrl);
            $path = $parsedUrl['path'] ?? '/';
            $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
            
            // Clean host - remove www
            $host = $parsedUrl['host'] ?? $request->getHost();
            $host = preg_replace('/^www\./i', '', $host);
            
            $scheme = app()->environment('local') ? $request->getScheme() : 'https';
            $backUrl = $scheme . '://' . $host . $path . $query;
        }

        return redirect()->to($backUrl, 302)->withCookie($cookie);
    }
}
