<div class="space-y-3">
    {{-- Search Bar Mobile --}}
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#a67c52] dark:text-[#b35200]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <input
            wire:model.live.debounce.400ms="search"
            type="text"
            placeholder="{{ __('messages.search_products_placeholder') }}"
            class="w-full bg-white dark:bg-[#1E140D] text-[#2C1A0E] dark:text-[#FDF5EC] placeholder-[#a68972] dark:placeholder-[#9E8B7D] text-[13px] font-semibold pl-10 pr-9 py-2.5 rounded-2xl border border-[#e8d5c4] dark:border-white/10 shadow-sm focus:outline-none focus:border-[#ff9100] dark:focus:border-[#b35200] focus:ring-2 focus:ring-[#ff9100]/20 dark:focus:ring-[#b35200]/20 transition-all"
        >
        @if($search !== '')
        <button
            wire:click="clearSearch"
            type="button"
            aria-label="Hapus Pencarian"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </button>
        @endif
    </div>

    {{-- Chips scrollable horizontal + Tombol Filter --}}
    <div class="flex items-center gap-2">
        {{-- Chips scrollable horizontal --}}
        <div class="flex items-center gap-2 overflow-x-auto flex-1 pb-0.5"
             style="-webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">
            <style>.mobile-chips::-webkit-scrollbar { display: none; }</style>

            {{-- Chip Semua Produk --}}
            <button
                wire:click="setCategory('{{ __('messages.all_products') }}')"
                class="shrink-0 px-3.5 py-2 rounded-xl text-[12px] font-semibold border transition-all duration-200 active:scale-95 cursor-pointer select-none
                    {{ ($activeCategory === __('messages.all_products') || empty($activeCategory) || (count($selectedTypes) === 0 && count($selectedCategories) === 0))
                        ? 'bg-[#ff9100] dark:bg-[#b35200] text-white border-[#ff9100] dark:border-[#b35200] shadow-md shadow-[#ff9100]/20'
                        : 'bg-white dark:bg-[#231811] text-[#7a5d48] dark:text-[#D8C6B6] border-[#e8d5c4] dark:border-white/10 hover:border-[#ff9100]/50' }}">
                {{ __('messages.all_products') }}
            </button>

            @foreach($allTypes as $type)
            <button
                wire:click="setCategory('{{ $type['name'] }}')"
                class="shrink-0 px-3.5 py-2 rounded-xl text-[12px] font-semibold border transition-all duration-200 active:scale-95 cursor-pointer select-none
                    {{ (in_array($type['name'], $selectedTypes) || (count($selectedTypes) === 0 && $activeCategory === $type['name']))
                        ? 'bg-[#ff9100] dark:bg-[#b35200] text-white border-[#ff9100] dark:border-[#b35200] shadow-md shadow-[#ff9100]/20'
                        : 'bg-white dark:bg-[#231811] text-[#7a5d48] dark:text-[#D8C6B6] border-[#e8d5c4] dark:border-white/10 hover:border-[#ff9100]/50' }}">
                {{ $type['name'] }}
            </button>
            @endforeach
        </div>

        {{-- Tombol Filter sticky kanan --}}
        <button
            @click="$dispatch('open-filter-modal', {
                allTypes: {{ Js::from($allTypes) }},
                allCategories: {{ Js::from($allCategories) }},
                types: {{ Js::from($selectedTypes) }},
                categories: {{ Js::from($selectedCategories) }},
                wireId: $wire.$id
            })"
            class="relative shrink-0 flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border transition-all duration-200 active:scale-90 hover:scale-105 cursor-pointer select-none
                {{ count($selectedTypes) > 0 || count($selectedCategories) > 0
                    ? 'bg-[#ff9100] dark:bg-[#b35200] text-white border-[#ff9100] dark:border-[#b35200] shadow-md shadow-[#ff9100]/25'
                    : 'bg-white dark:bg-[#231811] text-[#ff9100] dark:text-[#b35200] border-[#ff9100]/40 dark:border-[#b35200]/40 hover:bg-[#fff7ee] dark:hover:bg-[#2c1d15]' }}">
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            <span class="text-[10px] font-bold leading-none">Filter</span>
            @if(count($selectedTypes) > 0 || count($selectedCategories) > 0)
            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[#3d2b1f] dark:bg-[#130D08] border border-white/40 text-white text-[9px] font-black rounded-full flex items-center justify-center animate-pulse">
                {{ count($selectedTypes) + count($selectedCategories) }}
            </span>
            @endif
        </button>
    </div>
</div>
