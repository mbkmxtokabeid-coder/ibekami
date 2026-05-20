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

    <title>@yield('title', 'Admin — ' . config('app.name'))</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logos/logo ibekami (3).png') }}">
    {{-- apple-touch-icon dihapus: sinyal PWA --}}

    {{-- Preload critical fonts for better performance --}}
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/instrument-sans-latin-400-normal.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/instrument-sans-latin-600-normal.woff2') }}" crossorigin>
    
    {{-- Self-hosted fonts (faster than CDN) --}}
    <link rel="stylesheet" href="{{ asset('fonts/instrument-sans.css') }}">

    {{-- Highcharts — defer agar tidak blocking --}}
    <script src="https://code.highcharts.com/highcharts.js" defer></script>

    {{-- SweetAlert2 — defer agar tidak blocking --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-100 antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-full">

        {{-- Sidebar --}}
        <livewire:admin.sidebar />

        {{-- Main area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Top bar --}}
            <header class="flex items-center justify-between h-14 px-6 bg-[#1e2a3a] border-b border-white/10 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="text-gray-400 hover:text-white transition lg:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <span class="text-white font-semibold text-sm">@yield('page-title', 'Dashboard')</span>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-gray-400 text-sm">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-xs text-gray-400 hover:text-red-400 transition px-3 py-1.5 rounded-lg
                                       border border-white/10 hover:border-red-400/40">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
