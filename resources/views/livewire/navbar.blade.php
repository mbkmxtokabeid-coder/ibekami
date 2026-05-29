
<nav x-data="{ 
        mobileMenuOpen: false, 
        searchOpen: false, 
        langMenuOpen: false, 
        catalogMenuOpen: false, 
        scrolled: false,
        currentLocale: '{{ app()->getLocale() }}',
        isChangingLanguage: false,
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
     class="fixed top-0 inset-x-0 z-[100] transition-all duration-500 ease-out"
     :class="scrolled ? 'py-3' : 'py-4 lg:py-6'">
    
    <!-- Wrapper Utama agar melayang di tengah -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Floating Pill Container (Glassmorphism) -->
        <div class="bg-[#ffdbac]/85 backdrop-blur-xl border border-white/50 shadow-[0_8px_32px_rgba(255,145,0,0.05)] rounded-full px-4 sm:px-5 py-2.5 flex items-center justify-between transition-all duration-300"
             :class="scrolled ? 'bg-[#ffe8ca]/95 shadow-[0_12px_40px_rgba(255,145,0,0.08)]' : ''">
            
            <!-- 1. Logo Brand -->
            <a href="/" class="flex items-center gap-2.5 group shrink-0 outline-none">
                <img src="{{ asset('storage/logos/logo ibekami (3).webp') }}" 
                     alt="IBEKAMI Logo" 
                     width="36"
                     height="36"
                     class="w-8 h-8 sm:w-9 sm:h-9 object-contain group-hover:scale-105 transition-transform duration-300"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-[#ff9100] rounded-full items-center justify-center hidden">
                    <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 fill-white" viewBox="0 0 20 20"><path d="M10 2L3 7v11h5v-5h4v5h5V7z"/></svg>
                </div>
            </a>

            <!-- 2. Desktop Links -->
            <div class="hidden lg:flex items-center gap-1 xl:gap-2">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#ff9100] hover:bg-white/50 transition-all outline-none">
                    {{ __('messages.home') }}
                </a>
                <a href="{{ url('/#hot-deals') }}" class="px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#ff9100] hover:bg-white/50 transition-all outline-none">
                    {{ __('messages.hot_deals') }}
                </a>
                
                <!-- Katalog Dropdown (Desktop) -->
                <div class="relative">
                    <button @click="catalogMenuOpen = !catalogMenuOpen" @click.outside="catalogMenuOpen = false" 
                            class="flex items-center gap-1.5 px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#ff9100] hover:bg-white/50 transition-all outline-none"
                            :class="catalogMenuOpen ? 'bg-white/60 text-[#ff9100] shadow-sm' : ''">
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
                         class="absolute top-full left-0 mt-4 w-56 bg-white/95 backdrop-blur-xl border border-white/60 rounded-2xl shadow-xl overflow-hidden z-50 p-2">
                        <a href="{{ route('katalog') }}" class="block px-4 py-2.5 rounded-xl text-[14px] font-bold text-[#ff9100] bg-[#fff2e0]/50 hover:bg-[#fff2e0] transition-colors mb-1">{{ __('messages.all_products') }}</a>
                        
                        @forelse($productTypes as $type)
                            <a href="{{ route('katalog', ['type' => $type['slug']]) }}"
                               wire:key="desktop-type-{{ $type['id'] }}"
                               class="block px-4 py-2 rounded-xl text-[13px] font-medium text-[#5C3D28] hover:text-[#ff9100] hover:bg-black/5 transition-colors">
                                {{ $type['name'] }}
                            </a>
                        @empty
                            <div class="px-4 py-2 text-[13px] text-gray-400 italic">Belum ada kategori</div>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('mesin') }}" class="px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#ff9100] hover:bg-white/50 transition-all outline-none">
                    {{ __('messages.our_machines') }}
                </a>
                <a href="{{ url('/#footer') }}" class="px-4 py-2 rounded-full text-[#5C3D28] text-[13px] xl:text-[14px] font-semibold hover:text-[#ff9100] hover:bg-white/50 transition-all outline-none">
                    {{ __('messages.information') }}
                </a>
            </div>

            <!-- 3. Right Actions (Search, Language, CTA, Mobile Toggles) -->
            <div class="flex items-center gap-2 xl:gap-3">
                
                <!-- Search Bar (Desktop & Tablet) -->
                <div class="relative hidden md:block group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-[#8A6A54] group-focus-within:text-[#ff9100] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0Z"/></svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.350ms="search"
                           wire:keydown.enter="performSearch"
                           class="block w-32 xl:w-44 p-2 pl-9 text-[12px] font-medium text-[#2C1A0E] bg-white/40 border border-white/50 rounded-full focus:ring-2 focus:ring-[#ff9100]/30 focus:bg-white outline-none placeholder-[#8A6A54] transition-all shadow-inner" 
                           placeholder="{{ __('messages.search') }}...">
                </div>

                <!-- Language Dropdown -->
                <div class="relative shrink-0">
                    <button @click="langMenuOpen = !langMenuOpen" @click.outside="langMenuOpen = false" 
                            aria-label="Pilih Bahasa"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-full bg-white/40 border border-white/50 text-[#5C3D28] hover:text-[#ff9100] hover:bg-white transition-all outline-none shadow-sm"
                            :class="langMenuOpen ? 'bg-white ring-2 ring-[#ff9100]/30' : ''">
                        <span class="text-base leading-none lg:hidden" x-text="currentLocale === 'id' ? '🇮🇩' : '🇺🇸'"></span>
                        <span class="hidden lg:inline text-[12px] font-bold tracking-wide" x-text="currentLocale.toUpperCase()"></span>
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
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold w-full transition-colors"
                                :class="currentLocale === 'id' ? 'bg-[#fff2e0]/50 text-[#ff9100]' : 'hover:bg-black/5 text-[#5C3D28]'"
                                :disabled="isChangingLanguage">
                            <span class="text-base leading-none">🇮🇩</span> Indonesia
                        </button>
                        <button @click="debouncedChangeLanguage('en')" 
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold w-full transition-colors"
                                :class="currentLocale === 'en' ? 'bg-[#fff2e0]/50 text-[#ff9100]' : 'hover:bg-black/5 text-[#5C3D28]'"
                                :disabled="isChangingLanguage">
                            <span class="text-base leading-none">🇺🇸</span> English
                        </button>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="https://wa.me/6281707699999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
                   target="_blank"
                   rel="noopener"
                   @click.throttle.2000ms
                   class="hidden md:flex items-center justify-center bg-[#ff9100] text-[#2C1A0E] px-5 xl:px-6 py-2 rounded-full text-[13px] font-bold shadow-[0_4px_14px_rgba(255,145,0,0.3)] hover:shadow-[0_6px_20px_rgba(255,145,0,0.4)] hover:-translate-y-0.5 hover:bg-[#e68200] transition-all duration-300 outline-none shrink-0">
                    {{ __('messages.order') }}
                </a>

                <!-- Search Toggle Button (Khusus Mobile) -->
                <button @click="searchOpen = !searchOpen; mobileMenuOpen = false" 
                        aria-label="Cari Produk"
                        class="md:hidden flex items-center justify-center w-9 h-9 rounded-full bg-white/50 text-[#5C3D28] hover:bg-white hover:text-[#ff9100] transition-colors outline-none shadow-sm">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen; searchOpen = false" 
                        aria-label="Buka Menu"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-full bg-white/50 text-[#5C3D28] hover:bg-white hover:text-[#ff9100] transition-colors outline-none shadow-sm">
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
                       class="block w-full p-3.5 pl-10 text-[14px] font-medium text-[#2C1A0E] bg-[#fff2e0]/60 rounded-xl border-none focus:ring-2 focus:ring-[#ff9100]/40 outline-none placeholder-[#8A6A54]" 
                       placeholder="{{ __('messages.search_placeholder') }}">
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         class="absolute top-[76px] sm:top-[86px] inset-x-4 lg:hidden">
        
        <div class="bg-white/95 backdrop-blur-2xl border border-white/50 shadow-2xl rounded-3xl p-5 flex flex-col gap-2 max-h-[75vh] overflow-y-auto">
            <a href="{{ url('/') }}" class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#ff9100] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.home') }}</a>
            <a href="{{ url('/#hot-deals') }}" class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#ff9100] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.hot_deals') }}</a>
            
            <!-- Katalog Dropdown (Mobile) -->
            <div class="bg-[#fff2e0]/40 rounded-2xl">
                <button @click="catalogMenuOpen = !catalogMenuOpen" class="w-full flex justify-between items-center px-4 py-3 text-[15px] font-semibold text-[#2C1A0E] outline-none">
                    {{ __('messages.catalog') }}
                    <svg class="w-5 h-5 transition-transform duration-300 text-[#ff9100]" :class="{'rotate-180': catalogMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="catalogMenuOpen" x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="px-4 pb-3 flex flex-col gap-2">
                    <div class="w-full h-px bg-black/5 mb-1"></div>
                    <a href="{{ route('katalog') }}" class="px-3 py-2 rounded-xl bg-[#ff9100]/10 text-[14px] font-bold text-[#ff9100]">{{ __('messages.all_products') }}</a>
                    
                    @forelse($productTypes as $type)
                        <a href="{{ route('katalog', ['type' => $type['slug']]) }}"
                           wire:key="mobile-type-{{ $type['id'] }}"
                           class="px-3 py-2 rounded-xl text-[14px] font-medium text-[#5C3D28] hover:bg-[#fff2e0]">
                            {{ $type['name'] }}
                        </a>
                    @empty
                        <div class="px-3 py-2 text-[13px] text-gray-400 italic">Belum ada kategori</div>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('mesin') }}" class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#ff9100] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.our_machines') }}</a>
            <a href="{{ url('/#footer') }}" class="px-4 py-3 text-[#5C3D28] hover:bg-[#fff2e0]/80 hover:text-[#ff9100] rounded-2xl font-semibold text-[15px] transition-colors">{{ __('messages.information') }}</a>
            
            <div class="w-full h-px bg-black/5 my-2"></div>
            
            <a href="https://wa.me/6281707699999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
               target="_blank"
               rel="noopener"
               @click.throttle.2000ms
               class="w-full py-3.5 bg-[#ff9100] text-[#2C1A0E] rounded-2xl font-bold text-[15px] shadow-lg shadow-[#ff9100]/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2 outline-none">
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
