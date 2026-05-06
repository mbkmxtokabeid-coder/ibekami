<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Track WhatsApp button clicks
     * 
     * Rate Limited: 5 requests per minute per session
     * Prevents analytics distortion from double-clicks and bot crawling
     * 
     * Frontend also has throttle 2000ms for additional protection
     */
    public function trackWhatsAppClick(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'source' => 'required|string|max:100', // navbar, katalog, hero, etc.
            'product_id' => 'nullable|integer',
            'product_name' => 'nullable|string|max:255',
        ]);

        // Log WhatsApp click for analytics
        Log::channel('daily')->info('WhatsApp Click', [
            'source' => $validated['source'],
            'product_id' => $validated['product_id'] ?? null,
            'product_name' => $validated['product_name'] ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'session_id' => $request->session()->getId(),
            'timestamp' => now(),
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully.',
        ]);
    }

    /**
     * Get analytics summary (Admin only)
     */
    public function getWhatsAppClickStats(Request $request)
    {
        // TODO: Implement analytics dashboard
        // This would query logs or database to show:
        // - Total clicks per day/week/month
        // - Most clicked products
        // - Click sources (navbar, katalog, hero, etc.)
        // - Geographic distribution (based on IP)
        
        return response()->json([
            'success' => true,
            'message' => 'Analytics feature coming soon.',
        ]);
    }
}
