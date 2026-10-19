<footer id="footer" class="bg-[#ff9100] dark:bg-[#994500] pt-12 pb-20 sm:pb-16 lg:pb-12 px-5 sm:px-6 text-black transition-colors duration-200">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 mb-10 sm:mb-12">
        
        {{-- Media Sosial --}}
        <div class="sm:col-span-1 lg:col-span-1">
            <p class="text-black text-xs font-black uppercase tracking-[0.2em] mb-6 ">{{ __('messages.social_media') }}</p>
            <div class="flex flex-col gap-4 text-sm font-medium">
                <a href="https://www.instagram.com/ibekami.id/" target="_blank" rel="noopener noreferrer" aria-label="Instagram @ibekami.id" class="flex items-center gap-3 text-black hover:text-black/80 transition-all duration-300 group">
                    <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white group-hover:text-[#ff9100] dark:group-hover:text-[#994500] transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </div>
                    <span>{{ $instagramHandle }}</span>
                </a>
                <a href="https://www.tiktok.com/@ibekami.id" target="_blank" rel="noopener noreferrer" aria-label="TikTok @ibekami.id" class="flex items-center gap-3 text-black hover:text-black/80 transition-all duration-300 group">
                    <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white group-hover:text-[#ff9100] dark:group-hover:text-[#994500] transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                        </svg>
                    </div>
                    <span>{{ $tiktokHandle }}</span>
                </a>
            </div>
            
            <div class="mt-8">
                <p class="text-black text-xs font-black uppercase tracking-[0.2em] mb-4 opacity-95">{{ __('messages.need_help') }}</p>
                @php
                    $footerWaText = __('messages.wa_default_message');
                @endphp
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($footerWaText) }}" 
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-white-action inline-flex items-center gap-3 bg-white hover:bg-white/95 text-[#130D08] px-6 py-3.5 rounded-2xl text-sm font-extrabold shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    <span>{{ __('messages.ask_now') }}</span>
                </a>
            </div>
        </div>

        {{-- Kontak --}}
        <div class="sm:col-span-1 lg:col-span-1">
            <p class="text-black text-xs font-black uppercase tracking-[0.2em] mb-6 opacity-95">{{ __('messages.contact') }}</p>
            <div class="flex flex-col gap-5 text-sm">
                <div>
                    <a href="https://mail.google.com/mail/?to={{ $email }}&tf=cm"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Kirim email ke {{ $email }} via Gmail"
                       title="Kirim email ke {{ $email }} via Gmail"
                       class="group inline-flex items-center gap-3 text-black hover:text-black/80 transition-colors">
                        <svg class="w-5 h-5 text-black group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold break-all sm:break-normal">{{ $email }}</span>
                    </a>
                </div>
                <a href="{{ route('privacy-policy') }}" class="text-xs text-black/90 hover:text-black underline underline-offset-4 decoration-black/40 transition-all">
                    {{ __('messages.privacy_policy_terms') }}
                </a>
            </div>
            
            <div class="mt-8">
                <p class="text-black text-xs font-black uppercase tracking-[0.2em] mb-4 opacity-95">{{ __('messages.operating_hours') }}</p>
                <div class="flex flex-col gap-3 text-sm text-black">
                    <div class="flex items-start gap-2.5 sm:gap-3">
                        <svg class="w-4 h-4 text-black shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs sm:text-[13px] font-semibold text-black">{{ $operatingDays }}: <span class="font-normal opacity-95">{{ $operatingHours }}</span></span>
                    </div>
                    <div class="flex items-start gap-2.5 sm:gap-3 opacity-95">
                        <svg class="w-4 h-4 text-black shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span class="text-xs sm:text-[13px] font-semibold text-black">{{ $closedDays }}: <span class="font-normal">{{ __('messages.closed') }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="sm:col-span-2 lg:col-span-2">
            <p class="text-black text-xs font-black uppercase tracking-[0.2em] mb-6 opacity-95">{{ __('messages.our_location') }}</p>
            
            <div x-data="{ mapLoaded: false }" class="relative group block w-full rounded-2xl overflow-hidden border-4 border-white/20 shadow-xl transition-all duration-300 hover:border-white/40">
                <div class="relative w-full bg-[#fceddf] bg-map-container dark:bg-[#20150d] overflow-hidden transition-colors duration-200" style="height: 300px; min-height: 300px;">
                    
                    {{-- Google Maps Embed Iframe (Hanya dimuat ketika diklik) --}}
                    <template x-if="mapLoaded">
                        <iframe src="{{ $mapsEmbedUrl }}"
                                class="w-full h-full border-0 absolute inset-0 z-10"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Lokasi IBEKAMI di Google Maps"></iframe>
                    </template>

                    {{-- Preview Tampilan Peta Sebelum Diklik (Sesuai Desain Mockup) --}}
                    <div x-show="!mapLoaded" 
                         @click="mapLoaded = true"
                         class="absolute inset-0 z-10 flex flex-col items-center justify-center cursor-pointer select-none bg-[#fceddf] bg-map-container dark:bg-[#20150d] transition-all duration-300 hover:bg-[#fae3cb] dark:hover:bg-[#2a1c12] pb-16">
                        
                        {{-- Lingkaran Pin Lokasi --}}
                        <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-full bg-[#ead5be] bg-map-pin dark:bg-[#342419] flex items-center justify-center shadow-[inset_0_2px_4px_rgba(0,0,0,0.06)] mb-3 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#2C1A0E] dark:text-[#FDF5EC]" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        {{-- Label KLIK UNTUK PETA --}}
                        <span class="text-xs sm:text-[13px] font-black uppercase tracking-[0.2em] text-[#2C1A0E] dark:text-[#FDF5EC]">
                            {{ __('messages.click_for_map') }}
                        </span>
                    </div>

                    {{-- Tombol Tutup Peta (Tampil hanya saat iframe map dibuka) --}}
                    <button type="button"
                            x-show="mapLoaded"
                            style="display: none;"
                            @click="mapLoaded = false"
                            class="absolute top-3.5 right-3.5 sm:top-4 sm:right-4 z-30 bg-black/90 hover:bg-black text-black px-3.5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg backdrop-blur-sm flex items-center gap-1.5 transition-all hover:scale-105 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>{{ __('messages.close_map') }}</span>
                    </button>

                    {{-- Informasi alamat di bagian bawah peta --}}
                    <div class="absolute bottom-0 left-0 right-0 z-20 bg-white/95 dark:bg-[#1A120B]/95 backdrop-blur-md p-4 sm:p-5 border-t border-black/5 dark:border-white/10 transition-colors duration-200">
                        <p class="font-bold text-xs sm:text-sm leading-tight text-[#2C1A0E] dark:text-black uppercase tracking-tight">{{ $addressLine1 }}</p>
                        <p class="text-[11px] sm:text-xs text-[#5C3D28] dark:text-[#D8C6B6] mt-1">{{ $addressLine2 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="max-w-7xl mx-auto pt-6 border-t border-white/20 flex flex-col md:flex-row justify-between items-center md:items-start gap-3 sm:gap-6 text-black text-center md:text-left">
        <div class="text-center md:text-left">
            {{-- Mobile & Tablet: © + Kepanjangan di Atas, Hak Cipta di Baris Kedua --}}
            <div class="lg:hidden space-y-1">
                <p class="text-[11px] sm:text-xs font-medium text-black/95 leading-normal">
                    © 2025 {{ $companyName }} · {{ __('messages.company_full_name') }}
                </p>
                <p class="text-[10px] sm:text-[11px] text-black/90 font-normal tracking-wide">
                    {{ __('messages.all_rights_reserved') }}.
                </p>
            </div>

            {{-- Desktop (lg+): 1 Baris Penuh Rapi --}}
            <p class="hidden lg:block text-xs font-medium text-black/95 tracking-wide leading-normal">
                © 2025 {{ $companyName }} · {{ __('messages.company_full_name') }}
                <span class="mx-2 text-black/70">|</span>
                <span class="text-black/90 whitespace-nowrap">{{ __('messages.all_rights_reserved') }}.</span>
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0 mt-0.5 md:mt-0 pt-0.5 md:pt-0 leading-normal">
             <div class="w-1.5 h-1.5 rounded-full bg-white/60"></div>
             <span class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.2em] text-black opacity-90">{{ __('messages.indonesia') }}</span>
        </div>
    </div>
</footer>