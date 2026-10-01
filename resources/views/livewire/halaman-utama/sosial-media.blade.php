<section class="py-16 sm:py-20 px-5 sm:px-6 lg:px-8 bg-[#fdfaf7]">
    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="text-center max-w-xl mx-auto mb-10 sm:mb-12">
            <div class="flex items-center justify-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] uppercase tracking-[0.2em] mb-2 sm:mb-3">
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
                {{ __('messages.social_media') }}
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200]"></span>
            </div>
            <h2 class="font-['Poppins',sans-serif] text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#2C1A0E] tracking-tight leading-tight">
                {{ __('messages.follow_us') }}
            </h2>
            <p class="text-sm sm:text-base text-[#866b59] mt-2 sm:mt-3 font-medium leading-relaxed">
                {{ __('messages.follow_us_desc') }}
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
                         loading="lazy"
                         class="w-8 h-8 invert">
                </div>

                <div class="flex-1 min-w-0">
                    <div class="font-['Playfair_Display'] text-lg sm:text-xl font-bold text-[#2C1A0E] group-hover:text-[#010101] transition-colors duration-300">
                        TikTok
                    </div>
                    <div class="text-xs sm:text-sm text-[#866b59] mt-1 font-medium tracking-wide truncate">
                        @ibekami.id
                    </div>
                </div>

                <svg class="w-5 h-5 text-[#866b59]/40 group-hover:text-[#010101] group-hover:translate-x-1 transition-all duration-300 shrink-0"
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
                         loading="lazy"
                         class="w-8 h-8 invert">
                </div>

                <div class="flex-1 min-w-0">
                    <div class="font-['Playfair_Display'] text-lg sm:text-xl font-bold text-[#2C1A0E] group-hover:text-[#E1306C] transition-colors duration-300">
                        Instagram
                    </div>
                    <div class="text-xs sm:text-sm text-[#866b59] mt-1 font-medium tracking-wide truncate">
                        @ibekami.id
                    </div>
                </div>

                <svg class="w-5 h-5 text-[#866b59]/40 group-hover:text-[#E1306C] group-hover:translate-x-1 transition-all duration-300 shrink-0"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>

        </div>
    </div>
</section>
