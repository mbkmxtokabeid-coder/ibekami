<div>
<section class="py-16 sm:py-20 px-5 sm:px-6 lg:px-8 bg-[#fff2e0]">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 sm:mb-12">
            <div class="flex items-center gap-3 text-xs sm:text-[13px] font-bold text-[#ff9100] uppercase tracking-[0.2em] mb-3">
                {{ __('messages.shop_online') }}
                <span class="w-12 h-[2px] bg-[#ff9100]/50 rounded-full"></span>
            </div>
            <h2 class="font-['Playfair_Display'] text-3xl sm:text-4xl font-bold text-[#2C1A0E] leading-tight tracking-tight">{{ __('messages.official_marketplace') }}</h2>
            <p class="text-sm sm:text-base text-[#8A6A54] mt-3 font-medium leading-relaxed max-w-xl opacity-90">{{ __('messages.get_free_shipping') }}</p>
        </div>

        <div 
            x-data="{ hoveredCard: null }"
            class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6"
        >
            <!-- Tokopedia Card -->
            <a 
                href="{{ $tokopediaUrl }}" 
                target="_blank"
                rel="noopener noreferrer"
                @mouseenter="hoveredCard = 'tokopedia'"
                @mouseleave="hoveredCard = null"
                class="bg-white p-5 sm:p-6 rounded-3xl border border-[#ff9100]/10 flex items-center gap-5 sm:gap-6 group hover:border-[#ff9100]/40 hover:shadow-[0_8px_30px_rgba(255,145,0,0.12)] transition-all duration-300 hover:scale-[1.02] ease-out"
            >
                <!-- Logo Tokopedia -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#fff2e0] rounded-2xl flex-shrink-0 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 ease-out border border-[#ff9100]/5 overflow-hidden p-2">
                    <img src="{{ $tokopediaLogo }}" 
                         alt="Tokopedia" 
                         width="56"
                         height="56"
                         class="w-full h-full object-contain"
                         loading="lazy">
                </div>
                <div class="flex-1">
                    <div class="font-['Playfair_Display'] text-lg sm:text-xl font-bold text-[#2C1A0E] group-hover:text-[#03AC0E] transition-colors duration-300">Tokopedia</div>
                    <div class="text-xs sm:text-sm text-[#8A6A54]/80 mt-1 font-medium tracking-wide">{{ __('messages.free_shipping_buy_now') }}</div>
                </div>
                <span 
                    class="text-[#ff9100]/40 font-bold text-xl group-hover:text-[#03AC0E] group-hover:translate-x-1.5 transition-all duration-300 ease-out"
                    x-show="hoveredCard === 'tokopedia'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                >→</span>
            </a>

            <!-- Shopee Card -->
            <a 
                href="{{ $shopeeUrl }}" 
                target="_blank"
                rel="noopener noreferrer"
                @mouseenter="hoveredCard = 'shopee'"
                @mouseleave="hoveredCard = null"
                class="bg-white p-5 sm:p-6 rounded-3xl border border-[#ff9100]/10 flex items-center gap-5 sm:gap-6 group hover:border-[#ff9100]/40 hover:shadow-[0_8px_30px_rgba(255,145,0,0.12)] transition-all duration-300 hover:scale-[1.02] ease-out"
            >
                <!-- Logo Shopee -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#fff2e0] rounded-2xl flex-shrink-0 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 ease-out border border-[#ff9100]/5 overflow-hidden p-2">
                    <img src="{{ $shopeeLogo }}" 
                         alt="Shopee" 
                         width="56"
                         height="56"
                         class="w-full h-full object-contain"
                         loading="lazy">
                </div>
                <div class="flex-1">
                    <div class="font-['Playfair_Display'] text-lg sm:text-xl font-bold text-[#2C1A0E] group-hover:text-[#EE4D2D] transition-colors duration-300">Shopee</div>
                    <div class="text-xs sm:text-sm text-[#8A6A54]/80 mt-1 font-medium tracking-wide">{{ __('messages.free_shipping_ikhtiar_berkah') }}</div>
                </div>
                <span 
                    class="text-[#ff9100]/40 font-bold text-xl group-hover:text-[#EE4D2D] group-hover:translate-x-1.5 transition-all duration-300 ease-out"
                    x-show="hoveredCard === 'shopee'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                >→</span>
            </a>
        </div>
    </div>
</section>
</div>
