<footer id="footer" class="cv-auto bg-[#ff9100] dark:bg-[#130D08] pt-12 pb-20 sm:pb-16 lg:pb-12 px-5 sm:px-6 text-white transition-colors duration-200">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 mb-10 sm:mb-12">
        
        {{-- Media Sosial --}}
        <div class="sm:col-span-1 lg:col-span-1">
            <h4 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6 opacity-95">{{ __('messages.social_media') }}</h4>
            <div class="flex flex-col gap-4 text-sm font-medium">
                <a href="https://www.instagram.com/ibekami.id/" target="_blank" rel="noopener noreferrer" aria-label="Instagram @ibekami.id" class="flex items-center gap-3 text-white hover:text-white/80 transition-all duration-300 group">
                    <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white group-hover:text-[#ff9100] dark:group-hover:text-[#ff9100] transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>
                    <span>{{ $instagramHandle }}</span>
                </a>
                <a href="https://www.tiktok.com/@ibekami.id" target="_blank" rel="noopener noreferrer" aria-label="TikTok @ibekami.id" class="flex items-center gap-3 text-white hover:text-white/80 transition-all duration-300 group">
                    <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white group-hover:text-[#ff9100] dark:group-hover:text-[#ff9100] transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                        </svg>
                    </div>
                    <span>{{ $tiktokHandle }}</span>
                </a>
            </div>
            
            <div class="mt-8">
                <h4 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-4 opacity-95">{{ __('messages.need_help') }}</h4>
                @php
                    $footerWaText = "Halo Admin, saya tertarik dengan produk dari website Ibekami.id. Bisa bantu untuk info lebih lanjut?";
                @endphp
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($footerWaText) }}" 
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-white-action inline-flex items-center gap-3 !bg-white !text-black px-6 py-3.5 rounded-2xl text-sm font-extrabold shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 fill-current !text-black" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span class="!text-black">{{ __('messages.ask_now') }}</span>
                </a>
            </div>
        </div>

        {{-- Kontak --}}
        <div class="sm:col-span-1 lg:col-span-1">
            <h4 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6 opacity-95">{{ __('messages.contact') }}</h4>
            <div class="flex flex-col gap-5 text-sm">
                <div>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $email }}"
                       onclick="if (/Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) { window.location.href = 'mailto:{{ $email }}'; return false; }"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Kirim email ke {{ $email }}"
                       title="Kirim email ke {{ $email }}"
                       class="group inline-flex items-center gap-3 text-white hover:text-white/80 transition-colors">
                        <svg class="w-5 h-5 text-white group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold break-all sm:break-normal">{{ $email }}</span>
                    </a>
                </div>
                <a href="{{ route('privacy-policy') }}" class="text-xs text-white/90 hover:text-white underline underline-offset-4 decoration-white/40 transition-all">
                    {{ __('messages.privacy_policy_terms') }}
                </a>
            </div>
            
            <div class="mt-8">
                <h4 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-4 opacity-95">{{ __('messages.operating_hours') }}</h4>
                <div class="flex flex-col gap-3 text-sm text-white">
                    <div class="flex items-start gap-2.5 sm:gap-3">
                        <svg class="w-4 h-4 text-white shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs sm:text-[13px] font-semibold text-white">{{ $operatingDays }}: <span class="font-normal opacity-95">{{ $operatingHours }}</span></span>
                    </div>
                    <div class="flex items-start gap-2.5 sm:gap-3 opacity-95">
                        <svg class="w-4 h-4 text-white shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-xs sm:text-[13px] font-semibold text-white">{{ $closedDays }}: <span class="font-normal">{{ __('messages.closed') }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="sm:col-span-2 lg:col-span-2">
            <h4 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6 opacity-95">{{ __('messages.our_location') }}</h4>
            
            {{-- Seluruh kartu lokasi langsung membuka Google Maps; tidak menampilkan iframe perantara. --}}
            <a href="https://maps.app.goo.gl/o7soqw1UAc4AzDsH6"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="{{ __('messages.open_map') }} Google Maps"
               class="relative group block w-full rounded-2xl overflow-hidden border-4 border-white/20 shadow-xl transition-all duration-300 hover:border-white/40 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-4 focus-visible:ring-white/70">
                <div class="relative h-[290px] sm:h-[300px] lg:h-[300px] w-full bg-[#FFF2E0] bg-map-container dark:bg-[#1E140D] overflow-hidden transition-colors duration-200">
                    {{-- Preview statis agar kartu tetap terlihat konsisten di lokal dan produksi --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-[#FFF2E0] dark:bg-[#1E140D] text-[#2C1A0E] dark:text-white pb-14 transition-colors duration-200 group-hover:bg-[#FFE5C8] dark:group-hover:bg-[#251910]">
                        <div class="mb-4 p-4 bg-[#2C1A0E]/10 dark:bg-white/10 rounded-full shadow-lg transition-transform duration-300 group-hover:scale-110">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                        </div>
                        <p class="font-black text-sm uppercase tracking-widest">{{ __('messages.click_for_map') }}</p>
                    </div>

                    {{-- Label visual saja; kartu induk sudah menjadi tautan Google Maps --}}
                    <span class="btn-white-action absolute top-4 right-4 !bg-white !text-black px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-lg transition-all z-10">
                        {{ __('messages.open_map') }}
                    </span>

                    {{-- Informasi alamat --}}
                    <div class="absolute bottom-0 left-0 right-0 z-10 bg-white/95 dark:bg-[#1A120B]/95 backdrop-blur-md p-4 sm:p-5 border-t border-black/5 dark:border-white/10 transition-colors duration-200">
                        <p class="font-bold text-sm leading-tight text-[#2C1A0E] dark:text-white">{{ $addressLine1 }}</p>
                        <p class="text-xs text-[#5C3D28] dark:text-[#D8C6B6] mt-1">{{ $addressLine2 }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="max-w-7xl mx-auto pt-6 border-t border-white/20 flex flex-col md:flex-row justify-between items-center md:items-start gap-3 sm:gap-6 text-white text-center md:text-left">
        <div class="text-center md:text-left">
            {{-- Mobile & Tablet: © + Kepanjangan di Atas, Hak Cipta di Baris Kedua --}}
            <div class="lg:hidden space-y-1">
                <p class="text-[11px] sm:text-xs font-medium text-white/95 leading-normal">
                    © 2025 {{ $companyName }} · {{ __('messages.company_full_name') }}
                </p>
                <p class="text-[10px] sm:text-[11px] text-white/90 font-normal tracking-wide">
                    {{ __('messages.all_rights_reserved') }}.
                </p>
            </div>

            {{-- Desktop (lg+): 1 Baris Penuh Rapi --}}
            <p class="hidden lg:block text-xs font-medium text-white/95 tracking-wide leading-normal">
                © 2025 {{ $companyName }} · {{ __('messages.company_full_name') }}
                <span class="mx-2 text-white/70">|</span>
                <span class="text-white/90 whitespace-nowrap">{{ __('messages.all_rights_reserved') }}.</span>
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0 mt-0.5 md:mt-0 pt-0.5 md:pt-0 leading-normal">
             <div class="w-1.5 h-1.5 rounded-full bg-white/60"></div>
             <span class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.2em] text-white opacity-90">{{ __('messages.indonesia') }}</span>
        </div>
    </div>

    {{-- WhatsApp Floating Button --}}
    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($footerWaText) }}" 
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Chat via WhatsApp"
       class="fixed bottom-8 right-8 w-16 h-16 bg-[#25D366] rounded-2xl flex items-center justify-center shadow-[0_15px_40px_rgba(37,211,102,0.4)] hover:scale-110 hover:-rotate-3 transition-all duration-300 z-[200] group">
        <svg class="w-8 h-8 fill-white" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="absolute top-0 right-0 block h-4 w-4 rounded-full ring-4 ring-[#ff9100] dark:ring-[#b35200] bg-red-500 animate-pulse"></span>
    </a>
</footer>