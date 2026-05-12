<section class="py-16 sm:py-20 px-5 sm:px-6 lg:px-8 bg-[#fdfaf7]">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-10 sm:mb-12">
            <div class="flex items-center gap-3 text-xs sm:text-[13px] font-bold text-[#ff9100] uppercase tracking-[0.2em] mb-3">
                {{ __('messages.social_media') }}
                <span class="w-12 h-[2px] bg-[#ff9100]/50 rounded-full"></span>
            </div>
            <h2 class="font-['Playfair_Display'] text-3xl sm:text-4xl font-bold text-[#2C1A0E] leading-tight tracking-tight">
                Follow Us
            </h2>
            <p class="text-sm sm:text-base text-[#8A6A54] mt-3 font-medium leading-relaxed max-w-xl opacity-90">
                Ikuti kami untuk update produk terbaru, inspirasi desain, dan promo eksklusif.
            </p>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">

            {{-- TikTok --}}
            <a href="https://www.tiktok.com/@ibekami.id"
               target="_blank"
               rel="noopener noreferrer"
               class="bg-white p-5 sm:p-6 rounded-3xl border border-black/5 flex items-center gap-5 sm:gap-6 group
                      hover:border-[#010101]/20 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)]
                      transition-all duration-300 hover:scale-[1.02] ease-out">

                {{-- TikTok Icon --}}
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#010101] rounded-2xl flex-shrink-0 flex items-center justify-center
                            group-hover:scale-110 transition-transform duration-300 ease-out shadow-md overflow-hidden">
                    <img src="{{ asset('icons/tiktok.svg') }}"
                         alt="TikTok"
                         width="32" height="32"
                         class="w-8 h-8 invert"
                         loading="lazy">
                </div>

                <div class="flex-1 min-w-0">
                    <div class="font-['Playfair_Display'] text-lg sm:text-xl font-bold text-[#2C1A0E] group-hover:text-[#010101] transition-colors duration-300">
                        TikTok
                    </div>
                    <div class="text-xs sm:text-sm text-[#8A6A54]/80 mt-1 font-medium tracking-wide truncate">
                        @ibekami.id
                    </div>
                </div>

                <svg class="w-5 h-5 text-[#8A6A54]/40 group-hover:text-[#010101] group-hover:translate-x-1 transition-all duration-300 shrink-0"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>

            {{-- Instagram --}}
            <a href="https://www.instagram.com/ibekami.id"
               target="_blank"
               rel="noopener noreferrer"
               class="bg-white p-5 sm:p-6 rounded-3xl border border-black/5 flex items-center gap-5 sm:gap-6 group
                      hover:border-[#E1306C]/20 hover:shadow-[0_8px_30px_rgba(225,48,108,0.1)]
                      transition-all duration-300 hover:scale-[1.02] ease-out">

                {{-- Instagram Icon --}}
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex-shrink-0 flex items-center justify-center
                            group-hover:scale-110 transition-transform duration-300 ease-out shadow-md overflow-hidden
                            bg-gradient-to-br from-[#f09433] via-[#dc2743] to-[#bc1888]">
                    <img src="{{ asset('icons/instagram.svg') }}"
                         alt="Instagram"
                         width="32" height="32"
                         class="w-8 h-8 invert"
                         loading="lazy">
                </div>

                <div class="flex-1 min-w-0">
                    <div class="font-['Playfair_Display'] text-lg sm:text-xl font-bold text-[#2C1A0E] group-hover:text-[#E1306C] transition-colors duration-300">
                        Instagram
                    </div>
                    <div class="text-xs sm:text-sm text-[#8A6A54]/80 mt-1 font-medium tracking-wide truncate">
                        @ibekami.id
                    </div>
                </div>

                <svg class="w-5 h-5 text-[#8A6A54]/40 group-hover:text-[#E1306C] group-hover:translate-x-1 transition-all duration-300 shrink-0"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>

        </div>
    </div>
</section>
