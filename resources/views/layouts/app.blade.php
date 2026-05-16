<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    {{-- CRITICAL: Unregister semua Service Worker SEBELUM apapun di-load --}}
    {{-- SW lama bisa intercept request dan menyebabkan halaman blank + permission popup --}}
    <script>
        (function() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(regs) {
                    regs.forEach(function(r) { r.unregister(); });
                });
                // Hapus semua cache SW lama
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

    {{-- ── Global Filter Popup — di sini agar fixed positioning bekerja di semua device ── --}}
    <div id="global-filter-popup"
         x-data="{
             open: false,
             tempTypes: [],
             tempCategories: [],
             allTypes: [],
             allCategories: [],
             wireId: null,

             init() {
                 window.addEventListener('open-filter-modal', (e) => {
                     this.allTypes      = e.detail.allTypes      || [];
                     this.allCategories = e.detail.allCategories || [];
                     this.tempTypes     = [...(e.detail.types      || [])];
                     this.tempCategories= [...(e.detail.categories || [])];
                     this.wireId        = e.detail.wireId || null;
                     this.open = true;
                     document.body.style.overflow = 'hidden';
                 });
             },
             closeModal() {
                 this.open = false;
                 document.body.style.overflow = '';
             },
             toggleType(name) {
                 const idx = this.tempTypes.indexOf(name);
                 if (idx === -1) this.tempTypes.push(name);
                 else this.tempTypes.splice(idx, 1);
             },
             toggleCategory(name) {
                 const idx = this.tempCategories.indexOf(name);
                 if (idx === -1) this.tempCategories.push(name);
                 else this.tempCategories.splice(idx, 1);
             },
             apply() {
                 if (this.wireId) {
                     Livewire.find(this.wireId).call('applyMultiFilter', this.tempTypes, this.tempCategories);
                 }
                 this.closeModal();
             },
             reset() {
                 this.tempTypes = [];
                 this.tempCategories = [];
             }
         }">

        {{-- Backdrop --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"
             class="fixed inset-0 bg-black/50"
             style="display:none; z-index:99998;">
        </div>

        {{-- Bottom Sheet Panel --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl flex flex-col"
             style="display:none; z-index:99999; max-height:75vh;">

            {{-- Handle bar --}}
            <div class="flex justify-center pt-3 pb-0 shrink-0">
                <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-3 shrink-0 border-b border-gray-100">
                <h3 class="text-[17px] font-black text-[#3d2b1f]">Pilih Filter</h3>
                <button @click="closeModal()"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-[#3d2b1f] transition-all text-xl font-bold">
                    &#x2715;
                </button>
            </div>

            {{-- Scrollable content --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                <template x-if="allTypes.length > 0">
                    <div>
                        <p class="text-[13px] font-black text-[#3d2b1f] mb-3">Tipe Produk</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="type in allTypes" :key="type.name">
                                <button @click="toggleType(type.name)"
                                    :class="tempTypes.includes(type.name)
                                        ? 'bg-[#ff9100] text-white border-[#ff9100]'
                                        : 'bg-white text-[#3d2b1f] border-[#c4a882] hover:border-[#ff9100] hover:text-[#ff9100]'"
                                    class="px-4 py-2 rounded-2xl text-[13px] font-semibold border-2 transition-all">
                                    <span x-text="type.name"></span>
                                    <span class="ml-1 text-[11px] opacity-60" x-text="'(' + type.count + ')'"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="allCategories.length > 0">
                    <div>
                        <p class="text-[13px] font-black text-[#3d2b1f] mb-3">Kategori</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="cat in allCategories" :key="cat.name">
                                <button @click="toggleCategory(cat.name)"
                                    :class="tempCategories.includes(cat.name)
                                        ? 'bg-[#3d2b1f] text-white border-[#3d2b1f]'
                                        : 'bg-white text-[#3d2b1f] border-[#c4a882] hover:border-[#3d2b1f] hover:text-[#3d2b1f]'"
                                    class="px-4 py-2 rounded-2xl text-[13px] font-semibold border-2 transition-all">
                                    <span x-text="cat.name"></span>
                                    <span class="ml-1 text-[11px] opacity-60" x-text="'(' + cat.count + ')'"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

            </div>

            {{-- Footer --}}
            <div class="shrink-0 px-5 py-4 border-t border-gray-100 flex gap-3">
                <button @click="reset()"
                    class="flex-1 py-3.5 rounded-2xl border-2 border-[#ff9100] text-[#ff9100] font-bold text-[14px] hover:bg-[#fff2e0] transition-colors">
                    Atur Ulang
                </button>
                <button @click="apply()"
                    class="flex-1 py-3.5 rounded-2xl bg-[#ff9100] text-white font-bold text-[14px] hover:bg-[#e07d00] transition-colors shadow-md">
                    Terapkan
                </button>
            </div>

        </div>
    </div>

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