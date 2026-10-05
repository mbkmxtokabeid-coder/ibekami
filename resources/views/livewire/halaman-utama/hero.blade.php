<div>
@if($preloadImageUrl)
    @push('preload')
        <link rel="preload" as="image" href="{{ $preloadImageUrl }}" 
              imagesrcset="{{ $preloadImageMobileUrl }} 480w, {{ $preloadImageUrl }} 800w" 
              imagesizes="(max-width: 640px) 480px, 800px" 
              type="image/webp" fetchpriority="high">
    @endpush
@endif

<section class="relative bg-[#FFF2E0] flex items-center justify-center overflow-hidden px-4 pt-24 pb-12 sm:pt-28 sm:pb-14 lg:pt-32 lg:pb-16">
    
    <!-- Background Blurs (lebih ringan & subtle) -->
    <div class="absolute top-0 right-[-10%] w-[40vw] max-w-[420px] h-[40vw] max-h-[420px] bg-[#b35200]/10 blur-[80px] rounded-full animate-[pulse_6s_ease-in-out_infinite]"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[38vw] max-w-[380px] h-[38vw] max-h-[380px] bg-[#b35200]/5 blur-[100px] rounded-full animate-[pulse_8s_ease-in-out_infinite]"></div>

    <div class="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-6 items-center relative z-10">
        
        <!-- KONTEN KIRI -->
        <div class="lg:col-span-6 flex flex-col items-start space-y-4 sm:space-y-5 lg:pr-8">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/60 border border-white/50 backdrop-blur-sm shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FF9100] dark:bg-[#b35200] opacity-70"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FF9100] dark:bg-[#b35200]"></span>
                </span>
                <span class="text-[10px] sm:text-[11px] font-semibold tracking-[0.15em] uppercase text-[#5C3D28]">
                    {{ __('messages.made_in_medan') }}
                </span>
            </div>

            <!-- Headline -->
            <h1 class="font-playfair text-[38px] sm:text-[48px] lg:text-[60px] font-extrabold leading-[1.1] text-[#2C1A0E] dark:text-[#FDF5EC] tracking-tight">
                {{ __('messages.make_ideas_real') }} <br class="hidden sm:block">
                <span class="relative inline-block text-[#A64E2F] dark:text-[#ff9100]">
                    {{ __('messages.real_work') }}
                    <svg class="absolute w-full h-3 -bottom-2 left-0 text-[#A64E2F]/20 dark:text-[#ff9100]/25" viewBox="0 0 100 20" fill="currentColor">
                        <path d="M0 15 Q 25 5 50 15 T 100 15 L 100 20 L 0 20 Z"></path>
                    </svg>
                </span>
            </h1>

            <!-- Subheadline -->
            <p class="text-[14px] sm:text-[15px] text-[#5C3D28] dark:text-[#D8C6B6] leading-relaxed max-w-[460px] opacity-90">
                {{ __('messages.custom_souvenir') }}
            </p>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row gap-3.5 w-full sm:w-auto pt-2">
                
                <!-- Primary -->
                <a href="https://wa.me/62817076999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
                   target="_blank"
                   rel="noopener noreferrer"
                   @click.throttle.2000ms
                   class="group relative px-6 py-3.5 bg-[#FF9100] dark:bg-[#b35200] hover:bg-[#e07d00] dark:hover:bg-[#994500] text-[#2C1A0E] dark:text-white rounded-xl font-bold text-[13px] 
                   shadow-[0_4px_14px_rgba(255,145,0,0.35)] dark:shadow-[0_4px_14px_rgba(179,82,0,0.35)] hover:shadow-[0_8px_22px_rgba(255,145,0,0.45)] dark:hover:shadow-[0_8px_22px_rgba(179,82,0,0.45)] hover:-translate-y-[2px] active:scale-[0.98] transition-all duration-300 overflow-hidden text-center flex items-center justify-center">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        {{ __('messages.start_custom') }}
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform duration-300" viewBox="0 0 24 24" stroke="currentColor" fill="none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7-7 7M21 12H3"/>
                        </svg>
                    </span>
                </a>

                <!-- Secondary (Lihat Katalog dengan Border & Efek Responsif) -->
                <a href="{{ route('katalog') }}" 
                   class="group px-6 py-3.5 border-2 border-[#ff9100]/60 dark:border-[#b35200]/70 text-[#2C1A0E] dark:text-[#FDF5EC] rounded-xl font-bold text-[13px] 
                   hover:border-[#ff9100] dark:hover:border-[#b35200] hover:bg-[#ff9100] dark:hover:bg-[#b35200] hover:text-[#2C1A0E] dark:hover:text-white hover:-translate-y-[2px] 
                   hover:shadow-[0_8px_20px_rgba(255,145,0,0.25)] dark:hover:shadow-[0_8px_20px_rgba(179,82,0,0.25)] active:scale-[0.98] transition-all duration-300 text-center flex items-center justify-center gap-2">
                    {{ __('messages.view_catalog') }}
                    <svg class="w-4 h-4 opacity-75 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>

        <!-- KONTEN KANAN -->
        <div class="lg:col-span-6 relative w-full flex flex-col items-center justify-center mt-6 lg:mt-0"
             x-data="{ 
                 activeSlide: 0, 
                 slidesCount: {{ count($banners) }},
                 init() {
                     if (this.slidesCount > 1) {
                         setInterval(() => {
                              this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                         }, 5000);
                     }
                 }
             }">
            
            <!-- Frame — aspect-square agar carousel 1:1 tampil penuh -->
            <div style="aspect-ratio: 1/1;"
                 class="relative w-[85%] max-w-[480px] aspect-square bg-[#FFF2E0] rounded-2xl
            border border-white/60 shadow-lg shadow-[#b35200]/10 overflow-hidden transition-transform duration-500">

                @if(count($banners) > 0)
                    <!-- Sliding Wrapper -->
                    <div class="flex w-full h-full transition-transform duration-1000 ease-out"
                         :style="'transform: translateX(-' + (activeSlide * 100) + '%)'">
                        @foreach($banners as $index => $bannerItem)
                            <div class="w-full h-full shrink-0">
                                <img src="{{ $bannerItem['url'] }}"
                                     srcset="{{ $bannerItem['mobile_url'] }} 480w, {{ $bannerItem['url'] }} 800w"
                                     sizes="(max-width: 640px) 480px, 800px"
                                     alt="Banner utama IBEKAMI"
                                     width="800"
                                     height="800"
                                     @if($index === 0) loading="eager" fetchpriority="high" decoding="sync" @else loading="lazy" decoding="async" @endif
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback jika tidak ada banner -->
                    <div class="w-full h-full bg-gradient-to-br from-[#b35200]/20 to-[#FFB066]/20 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-20 h-20 mx-auto text-[#b35200]/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-[#5C3D28] text-sm">Banner belum tersedia</p>
                        </div>
                    </div>
                @endif

                <div class="absolute inset-0 bg-[#FF9100]/5 mix-blend-multiply pointer-events-none"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#FFF2E0] dark:from-[#130D08] via-transparent to-transparent opacity-40 pointer-events-none"></div>
            </div>

            <!-- Carousel Indicators (di bawah container foto) -->
            @if(count($banners) > 1)
                <div class="flex gap-1.5 mt-4 bg-[#b35200] dark:bg-[#b35200] border border-white/25 dark:border-white/15 px-3.5 py-1.5 rounded-full items-center shadow-md shadow-[#b35200]/25 dark:shadow-black/40 z-20">
                    @foreach($banners as $index => $bannerItem)
                        <button @click="activeSlide = {{ $index }}"
                                aria-label="Slide {{ $index + 1 }}"
                                class="w-6 h-6 flex items-center justify-center transition-all duration-300 focus:outline-none shrink-0"
                                type="button">
                            <span class="h-2 rounded-full transition-all duration-300"
                                  :class="activeSlide === {{ $index }} ? 'w-6 bg-white-pure shadow-sm' : 'w-2 bg-white/45 hover:bg-white-pure'"></span>
                        </button>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</section>
</div>
