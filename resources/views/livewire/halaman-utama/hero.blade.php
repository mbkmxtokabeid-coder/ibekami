<div>
@if($preloadImageUrl)
    @push('styles')
        <link rel="preload" as="image" href="{{ $preloadImageUrl }}" fetchpriority="high">
    @endpush
@endif

<section class="relative bg-[#FFF2E0] min-h-[85vh] flex items-center justify-center overflow-hidden px-4 py-16 lg:py-20 pt-28 lg:pt-32">
    
    <!-- Background Blurs (lebih ringan & subtle) -->
    <div class="absolute top-0 right-[-10%] w-[40vw] max-w-[420px] h-[40vw] max-h-[420px] bg-[#FF9100]/10 blur-[80px] rounded-full animate-[pulse_6s_ease-in-out_infinite]"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[38vw] max-w-[380px] h-[38vw] max-h-[380px] bg-[#FF9100]/5 blur-[100px] rounded-full animate-[pulse_8s_ease-in-out_infinite]"></div>

    <div class="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-6 items-center relative z-10">
        
        <!-- KONTEN KIRI -->
        <div class="lg:col-span-6 flex flex-col items-start space-y-6 lg:pr-8">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/60 border border-white/50 backdrop-blur-sm shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF9100] opacity-70"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF9100]"></span>
                </span>
                <span class="text-[10px] sm:text-[11px] font-semibold tracking-[0.15em] uppercase text-[#5C3D28]">
                    {{ __('messages.made_in_medan') }}
                </span>
            </div>

            <!-- Headline -->
            <h1 class="font-['Playfair_Display'] text-[38px] sm:text-[48px] lg:text-[60px] font-extrabold leading-[1.1] text-[#2C1A0E] tracking-tight">
                {{ __('messages.make_ideas_real') }} <br class="hidden sm:block">
                <span class="relative inline-block text-[#FF9100]">
                    {{ __('messages.real_work') }}
                    <svg class="absolute w-full h-3 -bottom-2 left-0 text-[#FF9100]/20" viewBox="0 0 100 20" fill="currentColor">
                        <path d="M0 15 Q 25 5 50 15 T 100 15 L 100 20 L 0 20 Z"></path>
                    </svg>
                </span>
            </h1>

            <!-- Subheadline -->
            <p class="text-[14px] sm:text-[15px] text-[#5C3D28] leading-relaxed max-w-[460px] opacity-90">
                {{ __('messages.custom_souvenir') }}
            </p>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto pt-2">
                
                <!-- Primary -->
                <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
                   target="_blank"
                   @click.throttle.2000ms
                   class="group relative px-6 py-3 bg-[#FF9100] text-white rounded-xl font-semibold text-[13px] 
                   shadow-md hover:shadow-lg hover:-translate-y-[2px] transition-all duration-300 overflow-hidden text-center">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        {{ __('messages.start_custom') }}
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                            <path stroke-width="2" d="M14 5l7 7-7 7M21 12H3"/>
                        </svg>
                    </span>
                </a>

                <!-- Secondary -->
                <a href="/katalog" 
                   class="px-6 py-3 text-[#FF9100] border border-[#FF9100]/30 rounded-xl font-semibold text-[13px] 
                   hover:bg-[#FF9100]/10 transition text-center">
                    {{ __('messages.view_catalog') }}
                </a>
            </div>

        </div>

        <!-- KONTEN KANAN -->
        <div class="lg:col-span-6 relative w-full flex items-center justify-center mt-6 lg:mt-0">
            
            <!-- Frame — aspect-square agar video 1:1 tampil penuh -->
            <!-- Frame — aspect-square agar carousel 1:1 tampil penuh -->
            <div x-data="{ 
                     activeSlide: 0, 
                     slidesCount: {{ count($banners) }},
                     init() {
                         if (this.slidesCount > 1) {
                             setInterval(() => {
                                 this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                             }, 5000);
                         }
                     }
                 }"
                 class="relative w-[85%] max-w-[480px] aspect-square bg-[#FFF2E0] rounded-2xl
            border border-white/60 shadow-lg shadow-[#FF9100]/10 overflow-hidden transition-transform duration-500">

                @if(count($banners) > 0)
                    <!-- Sliding Wrapper -->
                    <div class="flex w-full h-full transition-transform duration-1000 ease-out"
                         :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                        @foreach($banners as $index => $bannerItem)
                            <div class="w-full h-full shrink-0">
                                <img src="{{ $bannerItem['url'] }}"
                                     alt="Banner utama IBEKAMI"
                                     width="960"
                                     height="960"
                                     @if($index === 0) loading="eager" fetchpriority="high" decoding="sync" @else loading="lazy" decoding="async" @endif
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback jika tidak ada banner -->
                    <div class="w-full h-full bg-gradient-to-br from-[#FF9100]/20 to-[#FFB066]/20 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-20 h-20 mx-auto text-[#FF9100]/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-[#5C3D28] text-sm">Banner belum tersedia</p>
                        </div>
                    </div>
                @endif

                <div class="absolute inset-0 bg-[#FF9100]/5 mix-blend-multiply pointer-events-none"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#FFF2E0] via-transparent to-transparent opacity-40 pointer-events-none"></div>

                <!-- Carousel Indicators -->
                @if(count($banners) > 1)
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-20 bg-black/10 backdrop-blur-md px-3 py-1.5 rounded-full">
                        @foreach($banners as $index => $bannerItem)
                            <button @click="activeSlide = {{ $index }}"
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="activeSlide === {{ $index }} ? 'w-5 bg-[#FF9100]' : 'w-1.5 bg-white/60 hover:bg-white'"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Floating Card Rating -->
            <div class="absolute bottom-4 right-4 bg-white/80 backdrop-blur-md border border-white/60 p-3 rounded-xl shadow-md animate-[float_5s_ease-in-out_infinite_reverse]">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-yellow-300/30 rounded-lg flex items-center justify-center">★</div>
                    <div>
                        <p class="text-[9px] font-semibold text-[#8A6A54] uppercase">{{ __('messages.rating') }}</p>
                        <p class="text-[13px] font-bold text-[#2C1A0E]">5.0</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
@keyframes float {
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(-10px)}
}
</style>
</div>
