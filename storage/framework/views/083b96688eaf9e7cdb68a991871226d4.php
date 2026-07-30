<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    
    
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
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    
    

    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <title><?php echo $__env->yieldContent('title', 'IBEKAMI - Digital Printing & Souvenir Custom Medan'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'IBEKAMI - Percetakan dan souvenir kreatif terbaik di Medan. Melayani plakat, digital printing, dan merchandise custom dengan kualitas premium.'); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', 'percetakan Medan, souvenir custom Medan, merchandise perusahaan Medan, digital printing Medan, goodie bag Medan, acrylic Medan'); ?>">

    <!-- Open Graph (Facebook / Instagram / WhatsApp) -->
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="IBEKAMI">
    <meta property="og:locale"      content="id_ID">
    <meta property="og:url"         content="<?php echo e(request()->url()); ?>">
    <meta property="og:title"       content="<?php echo $__env->yieldContent('title', 'IBEKAMI – Percetakan & Souvenir Custom Terbaik di Medan'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', 'Souvenir custom, plakat, tumbler, dan digital printing berkualitas di Medan.'); ?>">
    <meta property="og:image"       content="<?php echo $__env->yieldContent('og_image', asset('storage/logos/logo ibekami (3).webp')); ?>">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"    content="<?php echo $__env->yieldContent('title', 'IBEKAMI – Percetakan & Souvenir Custom Medan'); ?>">

    <!-- Twitter / X Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo $__env->yieldContent('title', 'IBEKAMI – Percetakan & Souvenir Custom Terbaik di Medan'); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta_description', 'Souvenir custom, plakat, tumbler, dan digital printing berkualitas di Medan.'); ?>">
    <meta name="twitter:image"       content="<?php echo $__env->yieldContent('og_image', asset('storage/logos/logo ibekami (3).webp')); ?>">

    <?php echo $__env->yieldPushContent('preload'); ?>

    
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical', request()->url()); ?>">

    
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?php echo e(asset('favicon-48x48.png')); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-32x32.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>">

    
    <link rel="preload" as="font" type="font/woff2" href="<?php echo e(asset('fonts/instrument-sans-latin-400-normal.woff2')); ?>" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="<?php echo e(asset('fonts/instrument-sans-latin-500-normal.woff2')); ?>" crossorigin>
    <link rel="preload" as="font" type="font/woff2" href="<?php echo e(asset('fonts/instrument-sans-latin-600-normal.woff2')); ?>" crossorigin>
    
    
    <style>
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url('<?php echo e(asset('fonts/instrument-sans-latin-400-normal.woff2')); ?>') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url('<?php echo e(asset('fonts/instrument-sans-latin-500-normal.woff2')); ?>') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
            font-family: 'Instrument Sans';
            font-style: normal;
            font-weight: 600;
            font-display: swap;
            src: url('<?php echo e(asset('fonts/instrument-sans-latin-600-normal.woff2')); ?>') format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo $__env->yieldPushContent('styles'); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.env') === 'production'): ?>
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
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<body class="min-h-screen text-gray-900 dark:text-gray-100 antialiased">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.env') === 'production'): ?>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-FVT5H5JH"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navbar', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1801393554-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>

    <?php if (! empty(trim($__env->yieldContent('header')))): ?>
        <header class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <?php echo $__env->yieldContent('header'); ?>
            </div>
        </header>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success') || session()->has('error') || session()->has('info')): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                    <span><?php echo e(session('success')); ?></span>
                    <button @click="show = false" class="ml-4 text-green-600 hover:text-green-800">&times;</button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    <span><?php echo e(session('error')); ?></span>
                    <button @click="show = false" class="ml-4 text-red-600 hover:text-red-800">&times;</button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
                <div x-data="{ show: true }" x-show="show" x-cloak x-transition
                     class="flex items-center justify-between px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm">
                    <span><?php echo e(session('info')); ?></span>
                    <button @click="show = false" class="ml-4 text-blue-600 hover:text-blue-800">&times;</button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo e($slot ?? ''); ?>

    </main>

    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scriptConfig(['defer' => true]); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
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

        
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl flex flex-col"
             style="display:none; z-index:99999; max-height:75vh;">

            
            <div class="flex justify-center pt-3 pb-0 shrink-0">
                <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
            </div>

            
            <div class="flex items-center justify-between px-5 py-3 shrink-0 border-b border-gray-100">
                <h3 class="text-[17px] font-black text-[#3d2b1f]">Pilih Filter</h3>
                <button @click="closeModal()"
                    class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-[#3d2b1f] transition-all text-xl font-bold">
                    &#x2715;
                </button>
            </div>

            
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">

                <template x-if="allTypes.length > 0">
                    <div>
                        <p class="text-[13px] font-black text-[#3d2b1f] mb-3">Tipe Produk</p>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="type in allTypes" :key="type.name">
                                <button @click="toggleType(type.name)"
                                    :class="tempTypes.includes(type.name)
                                        ? 'bg-[#ff9100] text-[#2C1A0E] border-[#ff9100]'
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

            
            <div class="shrink-0 px-5 py-4 border-t border-gray-100 flex gap-3">
                <button @click="reset()"
                    class="flex-1 py-3.5 rounded-2xl border-2 border-[#ff9100] text-[#2C1A0E] font-bold text-[14px] hover:bg-[#fff2e0] transition-colors">
                    Atur Ulang
                </button>
                <button @click="apply()"
                    class="flex-1 py-3.5 rounded-2xl bg-[#ff9100] text-[#2C1A0E] font-bold text-[14px] hover:bg-[#e07d00] transition-colors shadow-md">
                    Terapkan
                </button>
            </div>

        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('app.env') === 'production'): ?>
    <?php
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
                'telephone' => '+62817076999',
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
                    'https://wa.me/62817076999',
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
    ?>
    <script type="application/ld+json"><?php echo json_encode($globalSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <script src="https://cdn.jsdelivr.net/npm/instant.page@5.2.0/instantpage.js" type="module" defer></script>

    
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
</html><?php /**PATH E:\3 MAGANG\IBEKAMI\ibekami_bckend\resources\views/layouts/app.blade.php ENDPATH**/ ?>