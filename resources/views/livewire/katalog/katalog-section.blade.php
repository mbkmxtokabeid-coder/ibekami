<div class="flex-1 min-w-0 w-full"
    x-data="{
        currentLimit: window.innerWidth < 768 ? 8 : 9,
        resizeDebounceTimer: null,
        
        init() {
            $wire.setPerPage(this.currentLimit);
        },
        
        handleResize() {
            // Debounce 200ms: hanya kalkulasi ulang setelah user berhenti resize
            clearTimeout(this.resizeDebounceTimer);
            this.resizeDebounceTimer = setTimeout(() => {
                let newLimit = window.innerWidth < 768 ? 8 : 9;
                if (this.currentLimit !== newLimit) {
                    this.currentLimit = newLimit;
                    $wire.setPerPage(newLimit);
                }
            }, 200);
        }
    }"
    @resize.window="handleResize()">

    {{-- Header: Status Produk + Active filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <p class="text-[14px] text-[#5C3D28]">
            {{ __('messages.showing') }} <strong class="text-[#2C1A0E]">{{ $this->paginatedData['total'] }} {{ __('messages.products') }}</strong>
        </p>

        {{-- Active filter chips --}}
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#A64E2F]/30 text-[#A64E2F] text-[12px] font-semibold rounded-full shadow-sm">
                {{ $activeCategory }}
                <button wire:click="resetFilters" class="hover:text-[#8C4126] focus:outline-none">&times;</button>
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#A64E2F]/30 text-[#A64E2F] text-[12px] font-semibold rounded-full shadow-sm">
                {{ $sortBy }}
                <button wire:click="resetFilters" class="hover:text-[#8C4126] focus:outline-none">&times;</button>
            </span>
            <button wire:click="resetFilters" class="text-[12px] font-semibold text-[#8A6A54] hover:text-[#A64E2F] transition-colors px-1 outline-none">
                {{ __('messages.reset_filters') }}
            </button>
        </div>
    </div>

    {{-- Product Grid --}}
    @if($this->paginatedData['total'] > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 relative">
            
            {{-- Loading Indicator --}}
            <div wire:loading wire:target="nextPage, previousPage, setPage, setPerPage, resetFilters" class="absolute inset-0 bg-[#D6CFBF]/60 backdrop-blur-sm z-20 rounded-2xl flex items-center justify-center">
                <span class="w-8 h-8 rounded-full border-4 border-[#A64E2F]/30 border-t-[#A64E2F] animate-spin"></span>
            </div>

            @foreach($this->paginatedData['items'] as $product)
                <a href="{{ route('katalog.detail', ['slug' => $product['slug']]) }}"
                   wire:navigate.hover
                   wire:key="product-{{ $product['id'] }}"
                   class="bg-[#FDFAF7] rounded-2xl overflow-hidden border border-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(44,26,14,0.1)] group cursor-pointer flex flex-col">

                    <div class="aspect-[4/3] bg-[#E8E3D8] relative overflow-hidden shrink-0">
                        <img src="{{ $product['img'] }}"
                             alt="{{ $product['name'] }}"
                             @if($loop->index < 4)
                                 loading="eager"
                                 fetchpriority="high"
                                 decoding="sync"
                             @else
                                 loading="lazy"
                                 decoding="async"
                             @endif
                             width="400"
                             height="300"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-[#2C1A0E]/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="bg-white text-[#A64E2F] font-bold px-4 py-2 rounded-lg text-xs translate-y-3 group-hover:translate-y-0 transition-transform duration-300 shadow-lg">
                                {{ __('messages.view_details') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 flex flex-col justify-start">
                        <p class="text-[10px] font-bold text-[#A64E2F] uppercase tracking-wider mb-1">
                            {{ $product['cat'] }}
                        </p>
                        <h3 class="text-[13px] font-bold text-[#2C1A0E] leading-snug line-clamp-2">
                            {{ $product['name'] }}
                        </h3>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($this->paginatedData['totalPages'] > 1)
            <div class="mt-12 flex flex-wrap justify-center items-center gap-2">
                
                <button wire:click="previousPage" @if($this->paginatedData['currentPage'] <= 1) disabled @endif
                    class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#A64E2F]/40 text-[#A64E2F] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#F5EDE8] transition-colors outline-none font-bold">
                    &larr;
                </button>

                <div class="hidden sm:flex items-center gap-1">
                    @for($i = 1; $i <= $this->paginatedData['totalPages']; $i++)
                        @if($i == 1 || $i == $this->paginatedData['totalPages'] || abs($i - $this->paginatedData['currentPage']) <= 1)
                            <button wire:click="setPage({{ $i }})"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl font-bold text-[13px] transition-colors outline-none
                                    {{ $this->paginatedData['currentPage'] == $i ? 'bg-[#A64E2F] text-white shadow-md shadow-[#A64E2F]/20' : 'border border-[#A64E2F]/20 text-[#A64E2F] hover:bg-[#F5EDE8]' }}">
                                {{ $i }}
                            </button>
                        @elseif(abs($i - $this->paginatedData['currentPage']) == 2)
                            <span class="w-6 text-center text-[#8A6A54] font-bold">...</span>
                        @endif
                    @endfor
                </div>

                <div class="sm:hidden text-[#5C3D28] text-[12px] font-bold px-3">
                    {{ __('messages.page') }} {{ $this->paginatedData['currentPage'] }} / {{ $this->paginatedData['totalPages'] }}
                </div>

                <button wire:click="nextPage" @if($this->paginatedData['currentPage'] >= $this->paginatedData['totalPages']) disabled @endif
                    class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#A64E2F]/40 text-[#A64E2F] disabled:opacity-30 disabled:cursor-not-allowed hover:bg-[#F5EDE8] transition-colors outline-none font-bold">
                    &rarr;
                </button>
            </div>
        @endif

    @else
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-24 text-center bg-[#FDFAF7] rounded-3xl border border-dashed border-[#A64E2F]/20">
            <svg class="w-12 h-12 text-[#C4B9A8] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[#8A6A54] font-semibold text-[14px]">{{ __('messages.no_products_found') }}</p>
            <button wire:click="resetFilters" class="mt-3 text-[#A64E2F] text-[13px] font-bold hover:underline outline-none">
                {{ __('messages.reset_all_filters') }}
            </button>
        </div>
    @endif


</div>
