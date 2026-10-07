
<nav x-data="{ 
        mobileMenuOpen: false, 
        searchOpen: false, 
        langMenuOpen: false, 
        catalogMenuOpen: false, 
        scrolled: false,
        currentLocale: '{{ app()->getLocale() }}',
        isChangingLanguage: false,
        isDark: document.documentElement.classList.contains('dark'),
        init() {
            this.$watch('mobileMenuOpen', (value) => {
                if (value) {
                    document.documentElement.style.overflow = 'hidden';
                    document.body.style.overflow = 'hidden';
                } else {
                    document.documentElement.style.overflow = '';
                    document.body.style.overflow = '';
                }
            });
        },
        toggleTheme() {
            this.isDark = !this.isDark;
            if (this.isDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        },
        debouncedChangeLanguage(locale) {
            if (this.isChangingLanguage || this.currentLocale === locale) return;
            this.isChangingLanguage = true;
            this.langMenuOpen = false;
            this.currentLocale = locale;
            fetch(`/lang/${locale}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.reload();
                else this.isChangingLanguage = false;
            })
            .catch(() => { this.isChangingLanguage = false; });
        }
    }" 
     @scroll.window.throttle.150ms="scrolled = (window.pageYOffset > 20)"
     @resize.window.debounce.100ms="if (window.innerWidth >= 1024) mobileMenuOpen = false"
     @keydown.escape.window="mobileMenuOpen = false; searchOpen = false;"
     class="fixed top-0 inset-x-0 z-[100] transition-all duration-500 ease-out"
     :class="scrolled ? 'py-3' : 'py-4 lg:py-6'">
    
    <!-- Wrapper Utama agar melayang di tengah -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
        
        <!-- Floating Pill Container (Glassmorphism) -->
        <div class="bg-[#ffdbac]/85 backdrop-blur-xl border border-white/50 shadow-[0_8px_32px_rgba(255,145,0,0.05)] rounded-full px-4 sm:px-5 py-2.5 flex items-center justify-between transition-all duration-300"
             :class="scrolled ? 'bg-[#ffe8ca]/95 shadow-[0_12px_40px_rgba(255,145,0,0.08)]' : ''">
            
            <!-- 1. Logo Brand -->
            <a href="/" class="flex items-center gap-2.5 group shrink-0 outline-none" title="IBEKAMI">
                <img src="{{ asset('logos/logo-ibekami.webp') }}" 
                     alt="IBEKAMI Logo" 
                     width="36"
                     height="36"
                     class="w-8 h-8 sm:w-9 sm:h-9 object-contain group-hover:scale-105 transition-transform duration-300 logo-light block dark:hidden">
                <img src="{{ asset('logos/logo-ibekami-dark.webp') }}" 
                     alt="IBEKAMI Logo" 
                     width="36"
                     height="36"
                     class="w-8 h-8 sm:w-9 sm:h-9 object-contain group-hover:scale-105 transition-transform duration-300 logo-dark hidden dark:block">
            </a>

            <!-- 2. Desktop Links -->
            <div class="hidden lg:flex items-center gap-1 xl:gap-2">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-full text-[13px] xl:text-[14px] font-semibold transition-all outline-none {{ request()->is('/') ? 'text-[#b35200] font-bold' : 'text-[#5C3D28] hover:text-[#b35200] hover:bg-white/50' }}">
                    {{ __('messages.home') }}
                </a>
                <!-- <a href="{{ url('/#hot-deals') }}" 
                   @click="if (document.getElementById('hot-deals')) { $event.preventDefault(); document.getElementById('hot-deals').scrollIntoView({ behavior: 'smooth' }); }"
                   class="px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#b35200] hover:bg-white/50 transition-all outline-none">
                    {{ __('messages.hot_deals') }}
                </a> -->
                
                <!-- Katalog Dropdown (Desktop) -->
                <div class="relative">
                    <button @click="catalogMenuOpen = !catalogMenuOpen" @click.outside="catalogMenuOpen = false" 
                            aria-label="{{ __('messages.catalog') }}, {{ app()->getLocale() === 'id' ? 'buka menu' : 'open menu' }}"
                            :aria-expanded="catalogMenuOpen ? 'true' : 'false'"
                            class="flex items-center gap-1.5 px-4 py-2 rounded-full text-[13px] xl:text-[14px] font-semibold transition-all outline-none {{ request()->routeIs('katalog*') ? 'text-[#b35200] font-bold' : 'text-[#5C3D28] hover:text-[#b35200] hover:bg-white/50' }}"
                            :class="catalogMenuOpen ? 'bg-white/60 text-[#b35200] shadow-sm' : ''">
                        {{ __('messages.catalog') }}
                        <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="{'rotate-180': catalogMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <!-- Isi Dropdown Katalog (Glassmorphism) -->
                    <div x-show="catalogMenuOpen" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                         class="absolute top-full left-0 mt-4 w-56 bg-white/95 dark:bg-[#1E140D]/95 backdrop-blur-xl border border-white/60 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden z-50 p-2">
                        @php
                            $isAllProductsSelected = $isKatalogPage && (
                                empty($selectedTypeSlug) && 
                                ($selectedCategory === __('messages.all_products') || empty($selectedCategory) || $selectedCategory === 'Semua Produk' || $selectedCategory === 'All Products')
                            );
                        @endphp
                        <a href="{{ route('katalog') }}" 
                           class="block px-4 py-2.5 rounded-xl text-[14px] transition-colors mb-1 {{ $isAllProductsSelected ? 'font-bold text-[#b35200] bg-[#fff2e0] dark:bg-[#b35200]/20' : 'font-medium text-[#5C3D28] dark:text-[#D8C6B6] hover:text-[#b35200] hover:bg-black/5 dark:hover:bg-white/5' }}">
                            {{ __('messages.all_products') }}
                        </a>
                        
                        @forelse($productTypes as $type)
                            @php
                                $isTypeSelected = $isKatalogPage && (
                                    (!empty($selectedTypeSlug) && $selectedTypeSlug === $type['slug']) ||
                                    (!empty($selectedCategory) && $selectedCategory === $type['name'])
                                );
                            @endphp
                            <a href="{{ route('katalog', ['type' => $type['slug']]) }}"
                               wire:key="desktop-type-{{ $type['id'] }}"
                               class="block px-4 py-2 rounded-xl text-[13px] transition-colors {{ $isTypeSelected ? 'font-bold text-[#b35200] bg-[#fff2e0] dark:bg-[#b35200]/20' : 'font-medium text-[#5C3D28] dark:text-[#D8C6B6] hover:text-[#b35200] hover:bg-black/5 dark:hover:bg-white/5' }}">
                                {{ $type['name'] }}
                            </a>
                        @empty
                            <div class="px-4 py-2 text-[13px] text-gray-400 italic">Belum ada kategori</div>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('mesin') }}" class="px-4 py-2 rounded-full text-[13px] xl:text-[14px] font-semibold transition-all outline-none {{ request()->routeIs('mesin') ? 'text-[#b35200] font-bold' : 'text-[#5C3D28] hover:text-[#b35200] hover:bg-white/50' }}">
                    {{ __('messages.our_machines') }}
                </a>
                <a href="{{ url('/#footer') }}" 
                   @click="if (document.getElementById('footer')) { $event.preventDefault(); document.getElementById('footer').scrollIntoView({ behavior: 'smooth' }); }"
                   class="px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#b35200] hover:bg-white/50 transition-all outline-none">
                    {{ __('messages.information') }}
                </a>
            </div>

            <!-- 3. Right Actions (Search, Language, CTA, Mobile Toggles) -->
            <div class="flex items-center gap-2 xl:gap-3">
                
                <!-- Search Bar (Desktop & Tablet) -->
                <!-- <div class="relative hidden md:block group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-[#8A6A54] group-focus-within:text-[#b35200] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0Z"/></svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.350ms="search"
                           wire:keydown.enter="performSearch"
                           class="block w-32 xl:w-44 p-2 pl-9 text-[12px] font-medium text-[#2C1A0E] bg-white/40 border border-white/50 rounded-full focus:ring-2 focus:ring-[#b35200]/30 focus:bg-white outline-none placeholder-[#8A6A54] transition-all shadow-inner" 
                           placeholder="{{ __('messages.search') }}...">
                </div> -->

                <!-- Language Dropdown -->
                <div class="relative shrink-0">
                    <button @click="langMenuOpen = !langMenuOpen" @click.outside="langMenuOpen = false" 
                            aria-label="{{ app()->getLocale() === 'id' ? 'ID - Pilih Bahasa' : 'EN - Choose Language' }}"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-full bg-white/40 border border-white/50 text-[#5C3D28] hover:text-[#b35200] hover:bg-white transition-all outline-none shadow-sm"
                            :class="langMenuOpen ? 'bg-white ring-2 ring-[#b35200]/30' : ''">
                        <span class="text-[12px] font-bold tracking-wide" x-text="currentLocale.toUpperCase()"></span>
                        <svg class="w-3 h-3 transition-transform duration-300" :class="{'rotate-180': langMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <!-- Dropdown Menu Bahasa -->
                    <div x-show="langMenuOpen" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         class="absolute right-0 mt-3 w-36 bg-white/95 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl overflow-hidden z-50 p-2">
                        <button @click="debouncedChangeLanguage('id')" 
                                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[13px] font-semibold w-full transition-colors"
                                :class="currentLocale === 'id' ? 'bg-[#fff2e0]/50 text-[#b35200]' : 'hover:bg-black/5 text-[#5C3D28]'"
                                :disabled="isChangingLanguage">
                            <span class="text-[12px] font-bold tracking-wider w-6 text-left">ID</span>
                            <span>Indonesia</span>
                        </button>
                        <button @click="debouncedChangeLanguage('en')" 
                                class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[13px] font-semibold w-full transition-colors"
                                :class="currentLocale === 'en' ? 'bg-[#fff2e0]/50 text-[#b35200]' : 'hover:bg-black/5 text-[#5C3D28]'"
                                :disabled="isChangingLanguage">
                            <span class="text-[12px] font-bold tracking-wider w-6 text-left">EN</span>
                            <span>English</span>
                        </button>
                    </div>
                </div>

                <!-- Dark Mode Toggle Button (Desktop & Mobile) -->
                <button @click="toggleTheme()" 
                        type="button"
                        aria-label="Toggle Dark / Light Mode"
                        class="flex items-center justify-center w-8.5 h-8.5 sm:w-9 sm:h-9 rounded-full bg-white/40 border border-white/50 text-[#5C3D28] hover:text-[#b35200] hover:bg-white transition-all outline-none shadow-sm hover:scale-105 active:scale-95 shrink-0"
                        :title="isDark ? '{{ app()->getLocale() === 'id' ? 'Mode Terang' : 'Light Mode' }}' : '{{ app()->getLocale() === 'id' ? 'Mode Gelap' : 'Dark Mode' }}'">
                    <!-- Sun icon: shown when dark (click to switch to light) -->
                    <svg x-show="isDark" x-cloak class="w-4.5 h-4.5 text-[#FFA026]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Moon icon: shown when light (click to switch to dark) -->
                    <svg x-show="!isDark" class="w-4 h-4 text-[#5C3D28]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- CTA Button -->
                <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Website%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
                   target="_blank"
                   rel="noopener"
                   @click.throttle.2000ms
                   class="hidden md:flex items-center justify-center bg-[#ff9100] dark:bg-[#b35200] text-[#2C1A0E] dark:text-white px-5 xl:px-6 py-2 rounded-full text-[13px] font-bold shadow-[0_4px_14px_rgba(255,145,0,0.3)] dark:shadow-[0_4px_14px_rgba(179,82,0,0.3)] hover:shadow-[0_6px_20px_rgba(255,145,0,0.4)] dark:hover:shadow-[0_6px_20px_rgba(179,82,0,0.4)] hover:-translate-y-0.5 hover:bg-[#e07d00] dark:hover:bg-[#994500] transition-all duration-300 outline-none shrink-0">
                    {{ __('messages.order') }}
                </a>

                <!-- Search Toggle Button (Khusus Mobile) -->
                <!-- <button @click="searchOpen = !searchOpen; mobileMenuOpen = false" 
                        aria-label="{{ __('messages.search_products') }}"
                        :aria-expanded="searchOpen ? 'true' : 'false'"
                        class="md:hidden flex items-center justify-center w-9 h-9 rounded-full bg-white/50 text-[#5C3D28] hover:bg-white hover:text-[#b35200] transition-colors outline-none shadow-sm">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button> -->

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen; searchOpen = false" 
                        aria-label="{{ app()->getLocale() === 'id' ? 'Buka Menu Navigasi' : 'Toggle Navigation' }}"
                        :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-full bg-white/50 text-[#5C3D28] hover:bg-white hover:text-[#b35200] transition-colors outline-none shadow-sm">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Search Dropdown -->
    <div x-show="searchOpen" x-cloak @click.outside="searchOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         class="absolute top-[76px] sm:top-[86px] inset-x-4 md:hidden">
        <div class="bg-white/95 backdrop-blur-2xl border border-white/50 shadow-2xl rounded-2xl p-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <svg class="w-4.5 h-4.5 text-[#8A6A54]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.350ms="search"
                       wire:keydown.enter="performSearch"
                       class="block w-full p-3.5 pl-10 text-[14px] font-medium text-[#2C1A0E] bg-[#fff2e0]/60 rounded-xl border-none focus:ring-2 focus:ring-[#b35200]/40 outline-none placeholder-[#8A6A54]" 
                       placeholder="{{ __('messages.search_placeholder') }}">
            </div>
        </div>
    </div>

    <!-- Mobile Menu Backdrop -->
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         @touchmove.prevent
         class="fixed inset-0 bg-black/40 backdrop-blur-xs z-10 lg:hidden"
         style="touch-action: none;"></div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" x-cloak 
         @click.outside="mobileMenuOpen = false"
         x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300 transform"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
         class="absolute top-[76px] sm:top-[86px] inset-x-4 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 sm:w-full sm:max-w-md z-30 lg:hidden">
        
        <div class="bg-white/95 dark:bg-[#1E140D]/95 backdrop-blur-2xl border border-white/50 dark:border-white/10 shadow-2xl rounded-3xl p-5 flex flex-col gap-2 max-h-[75vh] overflow-y-auto"
             style="overscroll-behavior: contain; -webkit-overflow-scrolling: touch;">
            <a href="{{ url('/') }}" 
               @click="mobileMenuOpen = false;"
               class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#b35200] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.home') }}</a>
            
            <!-- Katalog Dropdown (Mobile) -->
            <div class="bg-[#fff2e0]/40 rounded-2xl">
                <button @click="catalogMenuOpen = !catalogMenuOpen" 
                        aria-label="{{ __('messages.catalog') }}, {{ app()->getLocale() === 'id' ? 'buka menu' : 'open menu' }}"
                        :aria-expanded="catalogMenuOpen ? 'true' : 'false'"
                        class="w-full flex justify-between items-center px-4 py-3 text-[15px] font-semibold text-[#2C1A0E] outline-none active:scale-[0.99] transition-transform duration-200">
                    {{ __('messages.catalog') }}
                    <svg class="w-5 h-5 transition-transform duration-300 text-[#b35200]" :class="{'rotate-180': catalogMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="catalogMenuOpen" x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="px-4 pb-3 flex flex-col gap-2">
                    <div class="w-full h-px bg-black/5 dark:bg-white/10 mb-1"></div>
                    @php
                        $isAllProductsSelectedMobile = $isKatalogPage && (
                            empty($selectedTypeSlug) && 
                            ($selectedCategory === __('messages.all_products') || empty($selectedCategory) || $selectedCategory === 'Semua Produk' || $selectedCategory === 'All Products')
                        );
                    @endphp
                    <a href="{{ route('katalog') }}" 
                       class="px-3 py-2 rounded-xl text-[14px] transition-colors {{ $isAllProductsSelectedMobile ? 'bg-[#b35200]/10 text-[#b35200] font-bold dark:bg-[#b35200]/20' : 'text-[#5C3D28] dark:text-[#D8C6B6] font-medium hover:bg-[#fff2e0] dark:hover:bg-white/5' }}">
                        {{ __('messages.all_products') }}
                    </a>
                    
                    @forelse($productTypes as $type)
                        @php
                            $isTypeSelectedMobile = $isKatalogPage && (
                                (!empty($selectedTypeSlug) && $selectedTypeSlug === $type['slug']) ||
                                (!empty($selectedCategory) && $selectedCategory === $type['name'])
                            );
                        @endphp
                        <a href="{{ route('katalog', ['type' => $type['slug']]) }}"
                           wire:key="mobile-type-{{ $type['id'] }}"
                           class="px-3 py-2 rounded-xl text-[14px] transition-colors {{ $isTypeSelectedMobile ? 'bg-[#b35200]/10 text-[#b35200] font-bold dark:bg-[#b35200]/20' : 'text-[#5C3D28] dark:text-[#D8C6B6] font-medium hover:bg-[#fff2e0] dark:hover:bg-white/5' }}">
                            {{ $type['name'] }}
                        </a>
                    @empty
                        <div class="px-3 py-2 text-[13px] text-gray-400 italic">Belum ada kategori</div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('mesin') }}" 
               @click="mobileMenuOpen = false;"
               class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#b35200] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.our_machines') }}</a>
            <a href="{{ url('/#footer') }}" 
               @click="mobileMenuOpen = false; if (document.getElementById('footer')) { $event.preventDefault(); document.getElementById('footer').scrollIntoView({ behavior: 'smooth' }); }"
               class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#b35200] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.information') }}</a>
            
            <!-- Mobile Theme Switcher Row -->
            <div @click="toggleTheme()" class="flex items-center justify-between px-4 py-2.5 rounded-2xl bg-[#fff2e0]/60 dark:bg-[#2c1d15] my-1 cursor-pointer select-none transition-colors">
                <span class="text-[14px] font-semibold text-[#5C3D28] dark:text-[#FDF5EC] flex items-center gap-2.5">
                    <span class="text-base" x-text="isDark ? '🌙' : '☀️'"></span>
                    <span>{{ app()->getLocale() === 'id' ? 'Mode Gelap' : 'Dark Mode' }}</span>
                </span>
                <button type="button" 
                        role="switch"
                        :aria-checked="isDark"
                        aria-label="{{ app()->getLocale() === 'id' ? 'Aktifkan Mode Gelap' : 'Toggle Dark Mode' }}"
                        class="relative inline-flex h-7 w-[52px] shrink-0 cursor-pointer rounded-full p-0.5 transition-colors duration-300 ease-in-out focus:outline-none shadow-inner"
                        :class="isDark ? 'bg-[#b35200]' : 'bg-[#d8c5b5]'">
                    <span class="pointer-events-none inline-flex items-center justify-center h-6 w-6 transform rounded-full bg-white shadow-md transition-transform duration-300 ease-in-out text-[8px] font-black uppercase tracking-wider"
                          :class="isDark ? 'translate-x-[24px] text-[#b35200]' : 'translate-x-0 text-[#7a6452]'">
                        <span x-show="isDark">ON</span>
                        <span x-show="!isDark">OFF</span>
                    </span>
                </button>
            </div>

            <div class="w-full h-px bg-black/5 my-2"></div>
            
            <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Website%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
               target="_blank"
               rel="noopener"
               @click.throttle.2000ms
               class="w-full py-3.5 bg-[#ff9100] dark:bg-[#b35200] text-[#2C1A0E] dark:text-white rounded-2xl font-bold text-[15px] shadow-lg shadow-[#ff9100]/25 dark:shadow-[#b35200]/25 active:scale-[0.98] hover:bg-[#e07d00] dark:hover:bg-[#994500] transition-all flex items-center justify-center gap-2 outline-none">
                {{ __('messages.order_now') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</nav>

<style>
    /* Mencegah kedipan saat load dengan AlpineJS */
    [x-cloak] { display: none !important; }
</style>
