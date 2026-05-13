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

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 antialiased">

    {{ $slot }}

    @livewireScripts
</body>
</html>
