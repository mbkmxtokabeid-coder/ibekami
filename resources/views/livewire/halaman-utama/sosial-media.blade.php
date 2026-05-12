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
                            group-hover:scale-110 transition-transform duration-300 ease-out shadow-md">
                    <svg class="w-8 h-8 fill-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/>
                    </svg>
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
                            group-hover:scale-110 transition-transform duration-300 ease-out shadow-md
                            bg-gradient-to-br from-[#f09433] via-[#e6683c] via-[#dc2743] via-[#cc2366] to-[#bc1888]">
                    <svg class="w-8 h-8 fill-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
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
