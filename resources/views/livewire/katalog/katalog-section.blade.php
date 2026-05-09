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
                   wire:key="product-{{ $product['id'] }}"
                   class="bg-[#FDFAF7] rounded-2xl overflow-hidden border border-black/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(44,26,14,0.1)] group cursor-pointer flex flex-col">

                    <div class="aspect-[4/3] bg-[#E8E3D8] relative overflow-hidden shrink-0">
                        <img src="{{ $product['img'] }}"
                             alt="{{ $product['name'] }}"
                             loading="lazy"
                             decoding="async"
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

    {{-- WhatsApp CTA Mobile --}}
    <div class="lg:hidden bg-[#A64E2F] rounded-2xl p-6 text-center mt-10 shadow-lg shadow-[#A64E2F]/20">
        <p class="text-white font-bold text-[16px] mb-2">{{ __('messages.need_help') }}</p>
        <p class="text-white/80 text-[13px] mb-5">{{ __('messages.consultation_desc') }}</p>
        <a href="https://wa.me/628170769999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
           target="_blank"
           @click.throttle.2000ms
           class="flex items-center justify-center gap-2 bg-white text-[#A64E2F] font-bold text-[14px] px-5 py-3.5 rounded-xl hover:bg-[#FDF5F2] transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            {{ __('messages.ask_via_whatsapp') }}
        </a>
    </div>

</div>
