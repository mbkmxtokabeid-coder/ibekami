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
            return response()->json([
                'success' => false,
                'message' => 'Invalid locale. Only "id" and "en" are supported.',
            ], 400);
        }

        // Store locale in session
        $request->session()->put('locale', $locale);
        
        // Set application locale
        app()->setLocale($locale);

        // Log language switch for analytics (optional)
        Log::info('Language switched', [
            'locale' => $locale,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'locale' => $locale,
            'message' => 'Language switched successfully.',
        ]);
    }
}
