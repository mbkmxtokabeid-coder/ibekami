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
        // Get locale from session, default to 'id' (Indonesian)
        $locale = session('locale', 'id');
        
        // Set application locale
        app()->setLocale($locale);
        
        return $next($request);
    }
}
