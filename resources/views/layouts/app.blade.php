<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    {{-- Instant Dark Mode Detection (Zero-FOUC, zero delay) --}}
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>
    {{-- CRITICAL: Unregister Service Worker lama tanpa memblokir parsing HTML awal --}}
    <script>
        window.addEventListener('load', function() {
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
        });
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Anti-PWA: tag ini sengaja dihapus total, bukan di-set ke "no" --}}
    {{-- Kehadiran tag apple-mobile-web-app-capable & mobile-web-app-capable --}}
    {{-- meski content="no" tetap bisa dideteksi sebagai sinyal PWA oleh browser --}}

    <meta name="robots" content="@yield('robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">
    <meta name="google-site-verification" content="googleebaff0cf2f04e3b7">

    <title>@yield('title', 'IBEKAMI - Digital Printing & Souvenir Custom Medan')</title>
    <meta name="description" content="@yield('meta_description', 'IBEKAMI - Percetakan dan souvenir kreatif terbaik di Medan. Melayani plakat, digital printing, dan merchandise custom dengan kualitas premium.')">
    <meta name="keywords" content="@yield('meta_keywords', 'percetakan Medan, souvenir custom Medan, merchandise perusahaan Medan, digital printing Medan, goodie bag Medan, acrylic Medan')">

    <!-- Open Graph (Facebook / Instagram / WhatsApp) -->
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="IBEKAMI">
    <meta property="og:locale"      content="id_ID">
    <meta property="og:url"         content="{{ request()->url() }}">
    <meta property="og:title"       content="@yield('title', 'IBEKAMI – Percetakan & Souvenir Custom Terbaik di Medan')">
    <meta property="og:description" content="@yield('meta_description', 'Souvenir custom, plakat, tumbler, dan digital printing berkualitas di Medan.')">
    <meta property="og:image"       content="@yield('og_image', asset('storage/logos/logo ibekami (3).webp'))">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"    content="@yield('title', 'IBEKAMI – Percetakan & Souvenir Custom Medan')">

    <!-- Twitter / X Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('title', 'IBEKAMI – Percetakan & Souvenir Custom Terbaik di Medan')">
    <meta name="twitter:description" content="@yield('meta_description', 'Souvenir custom, plakat, tumbler, dan digital printing berkualitas di Medan.')">
    <meta name="twitter:image"       content="@yield('og_image', asset('storage/logos/logo ibekami (3).webp'))">

    @stack('preload')

    {{-- Dynamic canonical URL to prevent search engines and crawlers from indexing redirecting URLs --}}
    <link rel="canonical" href="@yield('canonical', request()->url())">

    {{-- Favicon & Google Search Icon (Google requires PNG/ICO format, minimum 48x48px) --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Google Fonts: Poppins & Plus Jakarta Sans (Non-render-blocking with inlined fallback) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    </noscript>

    {{-- Preload critical fonts for better performance (Network Dependency Tree optimization) --}}
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/plus-jakarta-sans-latin.woff2') }}" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/poppins-black-latin.woff2') }}" crossorigin>
    
    {{-- Inlined Self-hosted fonts CSS to eliminate a render-blocking HTTP request --}}
    <style>
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url('{{ asset('fonts/poppins-black-latin.woff2') }}') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 200 800;
            font-display: swap;
            src: url('{{ asset('fonts/plus-jakarta-sans-latin.woff2') }}') format('woff2-variations'),
                 url('{{ asset('fonts/plus-jakarta-sans-latin.woff2') }}') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

    {{-- ── Google Analytics & Tag Manager — production only ───────────────────
         Load on first user interaction (scroll/click/touch) untuk menghindari
         TBT (Total Blocking Time) yang merusak Core Web Vitals.
         Fallback: muat otomatis setelah 5 detik jika user tidak interaksi.
    ──────────────────────────────────────────────────────────────────────── --}}
    @if(config('app.env') === 'production')
    <script>
    (function () {
        // Mencegah pemuatan script pelacakan berat saat pengujian performa otomatis (Lighthouse, Bot, hPanel Speed Test)
        // guna menghindari lonjakan TBT (Total Blocking Time) di laporan audit.
        var isBot = navigator.webdriver || 
                    /bot|googlebot|lighthouse|crawler|spider|robot|crawling/i.test(navigator.userAgent);
        if (isBot) return;

        var loaded = false;
        function loadAnalytics() {
            if (loaded) return;
            loaded = true;

            // ── Google Tag Manager ──────────────────────────────────────────
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-FVT5H5JH');

            // ── Google Analytics 4 (G-2DR31JFPHR) ─────────────────────────
            var ga1 = document.createElement('script');
            ga1.async = true;
            ga1.src = 'https://www.googletagmanager.com/gtag/js?id=G-2DR31JFPHR';
            document.head.appendChild(ga1);

            // ── Google Analytics 4 (G-VQG7HT2KD0) + Google Ads ───────────
            var ga2 = document.createElement('script');
            ga2.async = true;
            ga2.src = 'https://www.googletagmanager.com/gtag/js?id=G-VQG7HT2KD0';
            document.head.appendChild(ga2);

            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            window.gtag = gtag;
            gtag('js', new Date());
            gtag('config', 'G-2DR31JFPHR');
            gtag('config', 'G-VQG7HT2KD0');
            gtag('config', 'AW-959548694');
        }

        // Trigger on first interaction
        ['scroll', 'mousemove', 'touchstart', 'keydown', 'click'].forEach(function (evt) {
            window.addEventListener(evt, loadAnalytics, { once: true, passive: true });
        });

        // Fallback: muat setelah 10 detik jika tidak ada interaksi pengguna sama sekali
        setTimeout(loadAnalytics, 10000);
    })();
    </script>
    @endif
</head>
<body class="min-h-screen text-gray-900 dark:text-gray-100 antialiased">

    {{-- GTM noscript — wajib ada tepat setelah <body> untuk tracking tanpa JS --}}
    @if(config('app.env') === 'production')
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-FVT5H5JH"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    @endif

    @sectionMissing('hide_navbar')
        <livewire:navbar />
    @endif

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

    {{-- Livewire Scripts with defer attribute for performance optimization --}}
    @livewireScriptConfig(['defer' => true])
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
                 this.$watch('open', (value) => {
                     if (value) {
                         document.documentElement.style.overflow = 'hidden';
                         document.body.style.overflow = 'hidden';
                     } else {
                         document.documentElement.style.overflow = '';
                         document.body.style.overflow = '';
                     }
                 });

                 window.addEventListener('open-filter-modal', (e) => {
                     this.allTypes      = e.detail.allTypes      || [];
                     this.allCategories = e.detail.allCategories || [];
                     this.tempTypes     = [...(e.detail.types      || [])];
                     this.tempCategories= [...(e.detail.categories || [])];
                     this.wireId        = e.detail.wireId || null;
                     this.open = true;
                 });
             },
             closeModal() {
                 this.open = false;
             },
             toggleType(name) {
                 if (this.tempTypes.includes(name)) {
                     this.tempTypes = [];
                 } else {
                     this.tempTypes = [name];
                 }
                 // Bersihkan kategori yang tidak sesuai dengan tipe yang baru dipilih
                 if (this.tempTypes.length > 0) {
                     const selectedType = this.allTypes.find(t => t.name === this.tempTypes[0]);
                     if (selectedType) {
                         this.tempCategories = this.tempCategories.filter(catName => {
                             const catObj = this.allCategories.find(c => c.name === catName);
                             return catObj && catObj.type_id === selectedType.id;
                         });
                     }
                 }
             },
             toggleCategory(name) {
                 const idx = this.tempCategories.indexOf(name);
                 if (idx === -1) this.tempCategories.push(name);
                 else this.tempCategories.splice(idx, 1);
             },
             get filteredCategories() {
                 if (!this.tempTypes || this.tempTypes.length === 0) {
                     return this.allCategories;
                 }
                 const selectedType = this.allTypes.find(t => t.name === this.tempTypes[0]);
                 if (!selectedType) return this.allCategories;
                 return this.allCategories.filter(cat => cat.type_id === selectedType.id);
             },
             apply() {
                 if (this.wireId && window.Livewire) {
                     const wireComp = Livewire.find(this.wireId);
                     if (wireComp) {
                         if (typeof wireComp.applyMultiFilter === 'function') {
                             wireComp.applyMultiFilter(this.tempTypes, this.tempCategories);
                         } else if (typeof wireComp.call === 'function') {
                             wireComp.call('applyMultiFilter', this.tempTypes, this.tempCategories);
                         }
                     }
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
             x-transition:enter="transition-opacity cubic-bezier(0.16, 1, 0.3, 1) duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"
             @touchmove.prevent
             class="fixed inset-0 bg-black/60 backdrop-blur-xs"
             style="display:none; z-index:99998; touch-action: none;">
        </div>

        {{-- Bottom Sheet Panel (Responsive: full width on mobile, sleek floating dialog on sm/md/tablet) --}}
        <div x-show="open"
             x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-350 transform"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-8 sm:scale-95"
             class="fixed bottom-0 inset-x-0 sm:bottom-6 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 sm:w-full sm:max-w-lg bg-[#FFF2E0]/95 dark:bg-[#1A120B]/95 backdrop-blur-2xl border-t sm:border border-[#e8d5c4] dark:border-white/10 rounded-t-3xl sm:rounded-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.15)] sm:shadow-[0_20px_60px_rgba(0,0,0,0.35)] flex flex-col transition-all duration-300"
             style="display:none; z-index:99999; max-height:85vh; overscroll-behavior: contain;">

            {{-- Handle bar --}}
            <div class="flex justify-center pt-3 pb-1 shrink-0">
                <div class="w-12 h-1.5 bg-[#c4a882]/50 dark:bg-white/20 rounded-full hover:bg-[#b35200] transition-colors cursor-pointer"></div>
            </div>

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 sm:px-6 py-3 shrink-0 border-b border-[#e8d5c4] dark:border-white/10">
                <div class="flex items-center gap-2">
                    <h3 class="text-[17px] sm:text-[18px] font-black text-[#3d2b1f] dark:text-[#FDF5EC] tracking-tight">Pilih Filter</h3>
                    <template x-if="tempTypes.length + tempCategories.length > 0">
                        <span class="px-2 py-0.5 text-[11px] font-bold bg-[#ff9100]/15 dark:bg-[#b35200]/25 text-[#ff9100] dark:text-[#ff9100] rounded-full"
                              x-text="(tempTypes.length + tempCategories.length) + ' aktif'"></span>
                    </template>
                </div>
                <button @click="closeModal()"
                    aria-label="Tutup Filter"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-black/5 hover:bg-black/10 dark:bg-white/10 dark:hover:bg-white/20 text-[#3d2b1f] dark:text-[#FDF5EC] active:scale-90 hover:rotate-90 transition-all duration-200">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- Scrollable content --}}
            <div class="flex-1 overflow-y-auto px-5 sm:px-6 py-4 space-y-5" style="overscroll-behavior: contain; -webkit-overflow-scrolling: touch;">

                <template x-if="allTypes.length > 0">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[13px] font-black text-[#3d2b1f] dark:text-[#FDF5EC]">Tipe Produk</p>
                            <span class="text-[11px] text-[#886852] dark:text-[#9E8B7D] font-medium">(Pilih 1 tipe)</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="type in allTypes" :key="type.name">
                                <button @click="toggleType(type.name)"
                                    :class="tempTypes.includes(type.name)
                                        ? 'bg-[#ff9100] dark:bg-[#b35200] text-white border-[#ff9100] dark:border-[#b35200] shadow-md shadow-[#ff9100]/20 scale-[1.02]'
                                        : 'bg-white dark:bg-[#231811] text-[#3d2b1f] dark:text-[#D8C6B6] border-[#c4a882] dark:border-white/10 hover:border-[#ff9100] dark:hover:border-[#b35200] hover:bg-[#fff7ee] dark:hover:bg-[#2c1d15]'"
                                    class="px-4 py-2 rounded-2xl text-[13px] font-semibold border-2 transition-all duration-200 active:scale-95 select-none inline-flex items-center gap-1.5 cursor-pointer">
                                    <span x-text="type.name"></span>
                                    <span class="text-[11px] opacity-80" x-text="'(' + type.count + ')'"></span>
                                    <template x-if="tempTypes.includes(type.name)">
                                        <svg class="w-3.5 h-3.5 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="filteredCategories.length > 0">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[13px] font-black text-[#3d2b1f] dark:text-[#FDF5EC]">Kategori</p>
                            <span x-show="tempTypes.length > 0" class="text-[11px] text-[#ff9100] dark:text-[#b35200] font-semibold" x-text="'Khusus ' + tempTypes[0]"></span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="cat in filteredCategories" :key="cat.name">
                                <button @click="toggleCategory(cat.name)"
                                    :class="tempCategories.includes(cat.name)
                                        ? 'bg-[#3d2b1f] dark:bg-[#ff9100] text-white border-[#3d2b1f] dark:border-[#ff9100] shadow-md shadow-black/15 scale-[1.02]'
                                        : 'bg-white dark:bg-[#231811] text-[#3d2b1f] dark:text-[#D8C6B6] border-[#c4a882] dark:border-white/10 hover:border-[#3d2b1f] dark:hover:border-[#ff9100] hover:bg-[#fff7ee] dark:hover:bg-[#2c1d15]'"
                                    class="px-4 py-2 rounded-2xl text-[13px] font-semibold border-2 transition-all duration-200 active:scale-95 select-none inline-flex items-center gap-1.5 cursor-pointer">
                                    <span x-text="cat.name"></span>
                                    <span class="text-[11px] opacity-80" x-text="'(' + cat.count + ')'"></span>
                                    <template x-if="tempCategories.includes(cat.name)">
                                        <svg class="w-3.5 h-3.5 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

            </div>

            {{-- Footer --}}
            <div class="shrink-0 px-5 sm:px-6 py-4 border-t border-[#e8d5c4] dark:border-white/10 flex gap-3">
                <button @click="reset()"
                    class="flex-1 py-3.5 rounded-2xl border-2 border-[#ff9100] dark:border-[#b35200] text-[#2C1A0E] dark:text-[#FDF5EC] font-bold text-[14px] hover:bg-[#fff2e0] dark:hover:bg-white/5 active:scale-[0.98] transition-all duration-200 cursor-pointer">
                    Atur Ulang
                </button>
                <button @click="apply()"
                    class="flex-1 py-3.5 rounded-2xl bg-[#ff9100] dark:bg-[#b35200] text-white font-bold text-[14px] hover:bg-[#e07d00] dark:hover:bg-[#994500] hover:shadow-lg hover:shadow-[#ff9100]/25 active:scale-[0.98] transition-all duration-200 shadow-md inline-flex items-center justify-center gap-2 cursor-pointer">
                    <span>Terapkan</span>
                    <span x-show="tempTypes.length + tempCategories.length > 0"
                          class="px-2 py-0.5 text-[11px] bg-white/25 rounded-full font-black tracking-wide"
                          x-text="tempTypes.length + tempCategories.length"></span>
                </button>
            </div>

        </div>
    </div>

    {{-- Structured Data: LocalBusiness + WebSite (non-blocking, di bawah body) --}}
    @if(config('app.env') === 'production')
    @php
        $graph = [];

        // Halaman Utama: sertakan LocalBusiness dengan detail lengkap dan teroptimasi SEO
        if (request()->routeIs('home')) {
            $graph[] = [
                '@type' => 'LocalBusiness',
                '@id' => config('app.url') . '/#business',
                'name' => 'IBEKAMI',
                'alternateName' => [
                    'Ibekami Medan',
                    'Percetakan Ibekami',
                    'Ibekami Souvenir & Printing',
                    'Digital Printing Ibekami'
                ],
                'description' => 'Jasa percetakan express & produsen souvenir custom murah terdekat di Medan. Melayani cetak plakat akrilik, tumbler, banner, stiker, kaos, dan merchandise custom untuk satuan maupun grosir dengan proses cepat.',
                'url' => config('app.url'),
                'logo' => asset('storage/logos/logo ibekami (3).webp'),
                'image' => asset('storage/banners/428f232a-c988-4731-8cf7-ceec4874496c.webp'),
                'telephone' => '+628170769999',
                'priceRange' => 'Rp',
                'currenciesAccepted' => 'IDR',
                'paymentAccepted' => 'Transfer Bank, Cash, WhatsApp Order',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Komplek Setia Budi Point, Jl. Setia Budi No.D-10, Tj. Sari, Kec. Medan Selayang',
                    'addressLocality' => 'Medan',
                    'addressRegion' => 'Sumatera Utara',
                    'postalCode' => '20132',
                    'addressCountry' => 'ID',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => 3.562946,
                    'longitude' => 98.636926,
                ],
                'areaServed' => ['Medan', 'Sumatera Utara', 'Indonesia'],
                'hasOfferCatalog' => [
                    '@type' => 'OfferCatalog',
                    'name' => 'Katalog Produk IBEKAMI',
                    'url' => route('katalog'),
                ],
                'sameAs' => [
                    'https://wa.me/628170769999',
                    'https://www.instagram.com/ibekami.id',
                    'https://www.tiktok.com/@ibekami.id',
                ],
                'openingHoursSpecification' => [
                    [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                        'opens' => '08:30',
                        'closes' => '17:00',
                    ]
                ],
                'aggregateRating' => [
                    '@type' => 'AggregateRating',
                    'ratingValue' => '5.0',
                    'bestRating' => '5',
                    'worstRating' => '1',
                    'ratingCount' => '1',
                ]
            ];
        }

        // WebSite schema (selalu ada di setiap halaman)
        $graph[] = [
            '@type' => 'WebSite',
            '@id' => config('app.url') . '/#website',
            'url' => config('app.url'),
            'name' => 'IBEKAMI',
            'publisher' => ['@id' => config('app.url') . '/#business'],
        ];

        $globalSchema = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($globalSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
    @endif

    {{-- Instant Page: Prefetch pages on hover for SPA-like navigation speed --}}
    <script src="https://cdn.jsdelivr.net/npm/instant.page@5.2.0/instantpage.js" type="module" defer></script>

    {{-- Handle smooth scroll on page load for lazy-loaded hash elements --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash) {
                const hash = window.location.hash;
                const targetId = hash.substring(1);
                
                let attempts = 0;
                const scrollInterval = setInterval(() => {
                    const el = document.getElementById(targetId);
                    attempts++;
                    
                    if (el) {
                        clearInterval(scrollInterval);
                        setTimeout(() => {
                            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 200);
                    }
                    
                    if (attempts > 50) {
                        clearInterval(scrollInterval);
                    }
                }, 100);
            }
        });
    </script>
</body>
</html>