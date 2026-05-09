<section id="katalog" class="py-14 md:py-20 px-4 bg-[#FFF2E0] relative overflow-hidden">

    <!-- Background Decorations -->
    <div class="absolute top-10 left-[-5%] w-72 h-72 bg-[#FF9100]/10 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-[-5%] w-64 h-64 bg-white/40 rounded-full blur-[60px] pointer-events-none"></div>

    <!-- Alpine.js Wrapper for Responsive -->
    <div 
        x-data="{
            updatePerPage() {
                if (window.innerWidth >= 1024) {
                    $wire.setPerPage(8)
                } else if (window.innerWidth >= 768) {
                    $wire.setPerPage(6)
                } else {
                    $wire.setPerPage(4)
                }
            }
        }"
        x-init="updatePerPage(); window.addEventListener('resize', () => updatePerPage())"
    >

        <div class="max-w-7xl mx-auto relative z-10">

            <!-- Header -->
            <div class="mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 text-[10px] font-semibold text-[#FF9100] uppercase tracking-[0.2em] mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#FF9100] animate-pulse"></span>
                        {{ __('messages.our_collection') }}
                    </div>

                    <h2 class="text-2xl md:text-4xl font-extrabold text-[#2C1A0E] leading-tight">
                        {{ __('messages.available_products') }}
                    </h2>

                    <p class="text-[12px] md:text-sm text-[#8A6A54] mt-2 max-w-md leading-relaxed">
                        {{ __('messages.made_with_love') }}
                    </p>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                @forelse($products as $product)
                <a href="{{ route('katalog.detail', ['slug' => $product['slug']]) }}"
                   wire:key="product-{{ $product['id'] }}"
                   class="group bg-white/90 rounded-3xl p-3 border border-black/5 
                          shadow-sm hover:shadow-md hover:shadow-[#FF9100]/10 
                          transition-all duration-300 ease-out 
                          hover:-translate-y-1 flex flex-col cursor-pointer">

                    <!-- Image Container — dimensi eksplisit mencegah CLS -->
                    <div class="h-[130px] md:h-[180px] rounded-2xl overflow-hidden bg-gradient-to-br from-[#FFF2E0] to-[#FFE5C8] mb-3">
                        <img 
                            src="{{ $product['img'] }}"
                            alt="{{ $product['name'] }}"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="300"
                            class="w-full h-full object-cover transition-all duration-500 
                                   group-hover:scale-[1.04]"
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/400x300?text={{ urlencode($product['name']) }}'"
                        >
                    </div>

                    <!-- Product Info -->
                    <div>
                        <div class="text-[10px] font-semibold text-[#FF9100] uppercase tracking-wide mb-1">
                            {{ $product['cat'] }}
                        </div>
                        <h3 class="text-[13px] md:text-sm font-semibold text-[#2C1A0E] leading-snug line-clamp-2">
                            {{ $product['name'] }}
                        </h3>
                    </div>

                </a>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="inline-flex flex-col items-center gap-3">
                        <svg class="w-16 h-16 text-[#8A6A54]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <p class="text-[#8A6A54] text-sm font-medium">{{ __('messages.no_products_available') }}</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($maxPages > 1)
            <div class="mt-10 flex justify-center items-center gap-3" wire:loading.class="opacity-50">

                <button wire:click="prevPage"
                        @disabled($page == 1)
                        class="px-4 py-2 rounded-xl border border-black/10 text-[12px] font-semibold 
                               hover:bg-[#FF9100]/10 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    ← {{ __('messages.prev') }}
                </button>

                <span class="text-sm font-semibold text-[#2C1A0E]">
                    {{ $page }} / {{ $maxPages }}
                </span>

                <button wire:click="nextPage"
                        @disabled($page >= $maxPages)
                        class="px-4 py-2 rounded-xl bg-[#FF9100] text-white text-[12px] font-semibold 
                               hover:opacity-90 transition disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ __('messages.next') }} →
                </button>

            </div>

            <!-- Loading Indicator -->
            <div wire:loading class="fixed bottom-8 right-8 z-50">
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-[#FF9100]/20 px-5 py-3 flex items-center gap-3">
                    <svg class="animate-spin w-5 h-5 text-[#FF9100]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span class="text-sm font-medium text-[#2C1A0E]">{{ __('messages.loading') }}</span>
                </div>
            </div>
            @endif

        </div>

    </div>

</section>

<style>
    /* Smooth image loading */
    img[data-src] {
        transition: opacity 0.3s ease-in-out;
    }
    
    img.loaded {
        opacity: 1 !important;
    }

    /* Line clamp for product names */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
