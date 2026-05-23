<div>
{{-- 
  Hot Deals Section - Gen Z 2026 Trend
  Performance Optimized: 
  - CSS Only Animations (Hardware Accelerated)
  - Lazy Loading Images
  - Asymmetric border-radius for aesthetic image shapes
  - Mobile 2-Columns Layout Optimized
--}}
<section id="hot-deals" class="py-16 md:py-24 px-4 bg-[#fdfaf7] relative overflow-hidden">
    
    <!-- Dekorasi Background Ringan (CSS Blur Blob) -->
    <div class="absolute top-0 right-[-5%] w-72 h-72 bg-[#ff9100]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-[-5%] w-80 h-80 bg-[#ff9100]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-6 mb-10 lg:mb-16">
            <div class="space-y-4">
                <!-- Badge Penawaran Spesial (Gen Z Style) -->
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-[#ff9100]/10 border border-[#ff9100]/20">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#ff9100] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#ff9100]"></span>
                    </span>
                    <span class="text-[10px] font-bold text-[#ff9100] uppercase tracking-widest">{{ __('messages.special_offer') }}</span>
                </div>
                
                <h2 class="font-['Playfair_Display'] text-3xl md:text-4xl lg:text-5xl font-black text-[#2C1A0E] tracking-tight">
                    {{ __('messages.hot_deals_this_month') }}
                </h2>
                
                <p class="text-[13px] sm:text-[14px] md:text-[15px] text-[#8A6A54] font-medium max-w-md leading-relaxed">
                    {{ __('messages.best_price_all_categories') }}
                </p>
            </div>
            
            <!-- Tombol CTA Header -->
            <a href="https://wa.me/6281707699999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
               target="_blank"
               @click.throttle.2000ms
               class="shrink-0 bg-white/50 backdrop-blur-sm border-2 border-[#ff9100]/80 text-[#ff9100] px-5 sm:px-6 py-3 sm:py-3.5 rounded-2xl text-[12px] sm:text-[13px] font-bold hover:bg-[#ff9100] hover:text-white hover:border-[#ff9100] hover:shadow-[0_8px_20px_rgba(255,145,0,0.25)] hover:-translate-y-1 transition-all duration-300 outline-none flex items-center justify-center gap-2 group w-full md:w-auto">
                {{ __('messages.ask_via_wa') }}
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <!-- Area Kartu Produk (Grid) dengan Alpine.js -->
        <div 
            x-data="{ 
                hoveredCard: null,
                setHovered(index) {
                    this.hoveredCard = index;
                },
                clearHovered() {
                    this.hoveredCard = null;
                }
            }"
            class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5 md:gap-6"
        >

            @forelse($deals as $index => $deal)
            <!-- Kartu Individu -->
            <a wire:key="deal-{{ $deal->id }}"
               href="https://wa.me/6281707699999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20{{ urlencode($deal->name) }}%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F"
               target="_blank"
               @click.throttle.2000ms
               @mouseenter="setHovered({{ $index }})"
               @mouseleave="clearHovered()"
               :class="hoveredCard === {{ $index }} ? 'scale-[1.02]' : ''"
               class="group bg-white rounded-[24px] sm:rounded-[32px] p-2 sm:p-2.5 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(255,145,0,0.12)] border border-black/5 hover:border-[#ff9100]/30 transition-all duration-500 hover:-translate-y-1 sm:hover:-translate-y-2 cursor-pointer flex flex-col h-full">
                
                <!-- Wrapper Gambar -->
                <div class="relative h-[120px] sm:h-[200px] w-full rounded-t-[16px] sm:rounded-t-[24px] rounded-b-[32px] sm:rounded-b-[48px] overflow-hidden bg-[#fff2e0] mb-3 sm:mb-5">
                    
                    <img src="{{ $deal->image_full_url }}" 
                         alt="{{ $deal->name }}" 
                         @if($index >= 4) loading="lazy" @endif 
                         decoding="async"
                         width="400"
                         height="300"
                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out origin-center">
                    
                    <!-- Overlay Halus -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#2C1A0E]/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    
                    <!-- Badge Hot Deal dengan Alpine.js animation -->
                    <div 
                        x-show="hoveredCard === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute top-2 right-2 bg-[#ff9100] text-white text-[9px] sm:text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider shadow-lg"
                    >
                        {{ __('messages.ask_price') }}
                    </div>
                </div>
                
                <!-- Konten Teks -->
                <div class="px-2 pb-3 sm:px-4 sm:pb-5 flex flex-col flex-1">
                    
                    <!-- Badge Hot Deal -->
                    <div class="mb-2 sm:mb-3" data-nosnippet>
                        <span class="inline-block bg-[#ff9100] text-white text-[9px] sm:text-[10px] font-bold px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-full uppercase tracking-widest shadow-md shadow-[#ff9100]/20">
                            {{ __('messages.hot_deal') }}
                        </span>
                    </div>
                    
                    <!-- Judul Produk -->
                    <h3 class="text-[13px] sm:text-[16px] font-bold text-[#2C1A0E] leading-[1.3] mb-4 sm:mb-5 group-hover:text-[#ff9100] transition-colors">
                        {{ $deal->name }}
                    </h3>
                    
                    <!-- Tautan Aksi -->
                    <div class="mt-auto flex items-center text-[11px] sm:text-[12px] font-bold text-[#ff9100] group-hover:text-[#e68200] transition-colors">
                        <span class="truncate">{{ __('messages.ask_price') }}</span> 
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 ml-1 sm:ml-1.5 transform group-hover:translate-x-1.5 sm:group-hover:translate-x-2 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                    
                </div>
            </a>
            @empty
            <div class="col-span-2 lg:col-span-4 text-center py-12">
                <div class="flex flex-col items-center gap-4">
                    <svg class="w-16 h-16 text-[#8A6A54]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-[#8A6A54] font-medium">{{ __('messages.no_product_types') }}</p>
                </div>
            </div>
            @endforelse
        </div>
        
    </div>
</section>
</div>
