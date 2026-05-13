<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Anti-PWA: tag ini sengaja dihapus total, bukan di-set ke "no" --}}
    {{-- Kehadiran tag apple-mobile-web-app-capable & mobile-web-app-capable --}}
    {{-- meski content="no" tetap bisa dideteksi sebagai sinyal PWA oleh browser --}}

    @if(config('app.env') === 'production')
        <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    @else
        <meta name="robots" content="noindex, nofollow">
    @endif

    <title>@yield('title', 'IBEKAMI - Digital Printing & Souvenir Custom Medan')</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logos/logo ibekami (3).png') }}">
    {{-- apple-touch-icon dihapus: sinyal PWA yang tidak diperlukan --}}

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen text-gray-900 dark:text-gray-100 antialiased">

    <livewire:navbar />

    @hasSection('header')
        <header class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                @yield('header')
            </div>
        </header>
    @endif

    @if (session()->has('success') || session()->has('error') || session()->has('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-2">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-4 text-red-600 hover:text-red-800">&times;</button>
                </div>
            @endif
            @if (session('info'))
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm">
                    <span>{{ session('info') }}</span>
                    <button @click="show = false" class="ml-4 text-blue-600 hover:text-blue-800">&times;</button>
                </div>
            @endif
        </div>
    @endif

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @stack('scripts')

    {{-- Unregister sisa service worker lama di browser user --}}
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                registrations.forEach(function(r) { r.unregister(); });
            });
        }
    </script>

    {{-- Structured Data: LocalBusiness + WebSite (non-blocking, di bawah body) --}}
    @if(config('app.env') === 'production')
    @php
        $globalSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'LocalBusiness',
                    '@id' => config('app.url') . '/#business',
                    'name' => 'IBEKAMI',
                    'description' => 'Jasa digital printing dan souvenir custom berkualitas tinggi di Medan. Melayani cetak banner, spanduk, kaos, mug, dan berbagai produk custom lainnya.',
                    'url' => config('app.url'),
                    'telephone' => '+628170769999',
                    'priceRange' => 'Rp',
                    'image' => asset('storage/logos/logo ibekami (3).png'),
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Medan',
                        'addressRegion' => 'Sumatera Utara',
                        'addressCountry' => 'ID',
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => 3.5952,
                        'longitude' => 98.6722,
                    ],
                    'openingHoursSpecification' => [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
                        'opens' => '08:00',
                        'closes' => '17:00',
                    ],
                    'sameAs' => ['https://wa.me/628170769999'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => config('app.url') . '/#website',
                    'url' => config('app.url'),
                    'name' => 'IBEKAMI',
                    'publisher' => ['@id' => config('app.url') . '/#business'],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($globalSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
    @endif
</body>
</html>