<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    {{-- CRITICAL: Unregister semua Service Worker SEBELUM apapun di-load --}}
    <script>
        (function() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(regs) {
                    regs.forEach(function(r) { r.unregister(); });
                });
                if ('caches' in window) {
                    caches.keys().then(function(keys) {
                        keys.forEach(function(key) { caches.delete(key); });
                    });
                }
            }
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Login — ' . config('app.name'))</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logos/logo ibekami (3).png') }}">
    {{-- apple-touch-icon dihapus: sinyal PWA --}}

    {{-- Preload critical fonts for better performance --}}
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/instrument-sans-latin-400-normal.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/instrument-sans-latin-600-normal.woff2') }}" crossorigin>
    
    {{-- Self-hosted fonts (faster than CDN) --}}
    <link rel="stylesheet" href="{{ asset('fonts/instrument-sans.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 antialiased">

    {{ $slot }}

    @livewireScripts
</body>
</html>
