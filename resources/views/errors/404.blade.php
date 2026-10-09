@extends('layouts.app')

@section('title', '404 - ' . __('messages.error_404_title') . ' | IBEKAMI')
@section('meta_description', __('messages.error_404_desc'))
@section('robots', 'noindex, follow')
@section('hide_navbar', 'true')

@section('content')
<div x-data="{
        langMenuOpen: false,
        currentLocale: '{{ app()->getLocale() }}',
        isChangingLanguage: false,
        isDark: document.documentElement.classList.contains('dark'),
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
    class="min-h-screen bg-[#fff2e0] dark:bg-[#130D08] flex flex-col items-center justify-between px-4 sm:px-6 lg:px-8 py-5 sm:py-6 relative overflow-hidden transition-colors duration-300">
    
    {{-- Header Khusus 404 Langsung di Top Halaman --}}
    <header class="w-full max-w-7xl mx-auto flex items-center justify-between z-20">
        {{-- Logo Brand --}}
        <a href="{{ route('home') }}" 
           id="logo-ibekami"
           class="flex items-center gap-2 group outline-none focus-visible:ring-2 focus-visible:ring-[#ff9100] rounded-xl" 
           title="IBEKAMI">
            <img src="{{ asset('logos/logo-ibekami.webp') }}" 
                 alt="IBEKAMI Logo" 
                 width="40"
                 height="40"
                 class="w-9 h-9 sm:w-10 sm:h-10 object-contain group-hover:scale-105 transition-transform duration-300 logo-light block dark:hidden">
            <img src="{{ asset('logos/logo-ibekami-dark.webp') }}" 
                 alt="IBEKAMI Logo" 
                 width="40"
                 height="40"
                 class="w-9 h-9 sm:w-10 sm:h-10 object-contain group-hover:scale-105 transition-transform duration-300 logo-dark hidden dark:block">
        </a>

        {{-- Top Right Controls (Bahasa & Mode Gelap/Terang) --}}
        <div class="flex items-center gap-2 sm:gap-2.5">
            {{-- Tombol Dropdown Bahasa --}}
            <div class="relative">
                <button @click="langMenuOpen = !langMenuOpen" @click.outside="langMenuOpen = false" 
                        type="button"
                        id="btn-language-toggle"
                        aria-label="{{ app()->getLocale() === 'id' ? 'ID - Pilih Bahasa' : 'EN - Choose Language' }}"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-full bg-white/70 dark:bg-[#1E140E]/70 backdrop-blur-md border border-[#ff9100]/25 dark:border-white/10 text-[#5C3D28] dark:text-[#FDF5EC] hover:text-[#b35200] dark:hover:text-[#FFA026] hover:bg-white dark:hover:bg-[#2A1D15] transition-all outline-none shadow-xs text-[12px] font-bold tracking-wide cursor-pointer"
                        :class="langMenuOpen ? 'ring-2 ring-[#ff9100]/40 bg-white dark:bg-[#2A1D15]' : ''">
                    <span x-text="currentLocale.toUpperCase()"></span>
                    <svg class="w-3 h-3 transition-transform duration-300" :class="{'rotate-180': langMenuOpen}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                {{-- Dropdown Menu Bahasa --}}
                <div x-show="langMenuOpen" x-cloak 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     class="absolute right-0 mt-2 w-36 bg-white/95 dark:bg-[#1E140E]/95 backdrop-blur-xl border border-[#ff9100]/15 dark:border-white/10 rounded-2xl shadow-xl overflow-hidden z-50 p-1.5">
                    <button @click="debouncedChangeLanguage('id')" 
                            type="button"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13px] font-semibold w-full transition-colors cursor-pointer"
                            :class="currentLocale === 'id' ? 'bg-[#fff2e0] dark:bg-[#ff9100]/20 text-[#b35200] dark:text-[#ff9100]' : 'hover:bg-black/5 dark:hover:bg-white/5 text-[#5C3D28] dark:text-[#FDF5EC]'"
                            :disabled="isChangingLanguage">
                        <span class="text-[12px] font-bold tracking-wider w-6 text-left">ID</span>
                        <span>Indonesia</span>
                    </button>
                    <button @click="debouncedChangeLanguage('en')" 
                            type="button"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13px] font-semibold w-full transition-colors cursor-pointer"
                            :class="currentLocale === 'en' ? 'bg-[#fff2e0] dark:bg-[#ff9100]/20 text-[#b35200] dark:text-[#ff9100]' : 'hover:bg-black/5 dark:hover:bg-white/5 text-[#5C3D28] dark:text-[#FDF5EC]'"
                            :disabled="isChangingLanguage">
                        <span class="text-[12px] font-bold tracking-wider w-6 text-left">EN</span>
                        <span>English</span>
                    </button>
                </div>
            </div>

            {{-- Tombol Toggle Mode Gelap / Terang --}}
            <button @click="toggleTheme()" 
                    type="button"
                    id="btn-theme-toggle"
                    :aria-label="currentLocale === 'id' ? (isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap') : (isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode')"
                    :title="currentLocale === 'id' ? (isDark ? 'Mode Terang' : 'Mode Gelap') : (isDark ? 'Light Mode' : 'Dark Mode')"
                    class="flex items-center justify-center w-9 h-9 rounded-full bg-white/70 dark:bg-[#1E140E]/70 backdrop-blur-md border border-[#ff9100]/25 dark:border-white/10 text-[#5C3D28] dark:text-[#FDF5EC] hover:text-[#b35200] dark:hover:text-[#FFA026] hover:bg-white dark:hover:bg-[#2A1D15] transition-all outline-none shadow-xs hover:scale-105 active:scale-95 shrink-0 cursor-pointer">
                <!-- Sun icon (shown when dark, click to switch to light) -->
                <svg x-show="isDark" x-cloak class="w-4.5 h-4.5 text-[#FFA026]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon icon (shown when light, click to switch to dark) -->
                <svg x-show="!isDark" class="w-4 h-4 text-[#5C3D28]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>
    </header>

    {{-- Center Card Container --}}
    <div class="w-full max-w-md mx-auto my-auto py-8 relative z-10">
        
        {{-- Minimalist Card Container --}}
        <div class="bg-white dark:bg-[#1E140E] rounded-[32px] p-8 shadow-[0_20px_50px_rgba(166,78,47,0.12)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-[#ff9100]/10 dark:border-[#ff9100]/20 text-center">
            
            {{-- Minimalist Icon --}}
            <div class="w-20 h-20 mx-auto mb-5 bg-gradient-to-br from-[#ff9100]/15 via-[#ff9100]/10 to-transparent dark:from-[#ff9100]/25 dark:via-[#ff9100]/10 dark:to-transparent rounded-full flex items-center justify-center border border-[#ff9100]/20">
                <svg class="w-10 h-10 text-[#ff9100]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Error Code Badge / Label with Gradient --}}
            <div class="mb-4">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-gradient-to-r from-[#ff9100]/10 via-[#ffa026]/20 to-[#ff7300]/10 dark:from-[#ff9100]/20 dark:via-[#ffa026]/30 dark:to-[#ff7300]/20 border border-[#ff9100]/25 dark:border-[#ff9100]/40 shadow-xs shadow-[#ff9100]/10">
                    <span class="bg-gradient-to-r from-[#b35200] via-[#e65c00] to-[#ff9100] dark:from-[#ffa026] dark:via-[#ffb85a] dark:to-[#ff8c1a] bg-clip-text text-transparent">
                        {{ __('messages.error_404_badge') }}
                    </span>
                </span>
            </div>

            {{-- Title --}}
            <h1 class="font-['Playfair_Display',serif] text-2xl sm:text-3xl font-bold text-[#2C1A0E] dark:text-[#FDF5EC] mb-2.5">
                {{ __('messages.error_404_title') }}
            </h1>

            {{-- Message Description --}}
            <p class="text-[#7a6452] dark:text-[#B59D89] text-[14px] sm:text-[15px] leading-relaxed mb-7 max-w-xs mx-auto">
                @if(isset($exception) && !empty($exception->getMessage()))
                    {{ $exception->getMessage() }}
                @else
                    {{ __('messages.error_404_desc') }}
                @endif
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- 1. Kembali (Back Button) - Sebelah Kiri --}}
                <button type="button" 
                        id="btn-back-previous"
                        onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'"
                        class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 py-3 px-5 bg-white dark:bg-[#1E140E] border border-[#ff9100]/30 hover:border-[#ff9100]/60 rounded-xl font-bold text-sm shadow-xs hover:shadow-[0_4px_14px_rgba(255,145,0,0.15)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 group cursor-pointer">
                    <svg class="w-4 h-4 shrink-0 text-[#ff9100] group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="bg-gradient-to-r from-[#b35200] to-[#ff9100] dark:from-[#ffa026] dark:to-[#ff7300] bg-clip-text text-transparent group-hover:from-[#943f00] group-hover:to-[#e66a00] transition-all">
                        {{ __('messages.back_to_previous') }}
                    </span>
                </button>

                {{-- 2. Beranda - Sebelah Kanan --}}
                <a href="{{ route('home') }}" 
                   id="btn-back-home"
                   class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 py-3 px-5 bg-gradient-to-r from-[#ff9100] via-[#f58200] to-[#e66a00] hover:from-[#e68200] hover:via-[#d97200] hover:to-[#cc5500] text-white rounded-xl font-bold text-sm shadow-[0_4px_14px_rgba(255,145,0,0.25)] hover:shadow-[0_6px_20px_rgba(255,145,0,0.35)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>{{ __('messages.back_to_home') }}</span>
                </a>
            </div>

        </div>

    </div>

    {{-- Subtle Static Copyright Note at Bottom --}}
    <footer class="w-full text-center text-xs text-[#8A6A54] dark:text-[#9E8B7D] pb-1 z-10 shrink-0">
        © {{ date('Y') }} IBEKAMI • {{ __('messages.all_rights_reserved') }}
    </footer>
</div>
@endsection
