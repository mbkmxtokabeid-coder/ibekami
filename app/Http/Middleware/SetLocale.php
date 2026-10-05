<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session or cookie, default to 'id' (Indonesian)
        $locale = $request->cookie('locale') ?? session('locale', 'id');
        
        if (!in_array($locale, ['id', 'en'])) {
            $locale = 'id';
        }
        
        if ($request->hasSession()) {
            session(['locale' => $locale]);
        }
        
        app()->setLocale($locale);
        
        $response = $next($request);

        if ($request->cookie('locale') !== $locale && method_exists($response, 'withCookie')) {
            $response->withCookie(cookie()->forever('locale', $locale));
        }
        
        return $response;
    }
}
