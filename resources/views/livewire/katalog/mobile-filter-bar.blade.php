<div class="flex items-center gap-2">
    {{-- Chips scrollable horizontal --}}
    <div class="flex items-center gap-2 overflow-x-auto flex-1 pb-0.5"
         style="-webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">
        <style>.mobile-chips::-webkit-scrollbar { display: none; }</style>

        @foreach($allTypes as $type)
        <button
            wire:click="setCategory('{{ $type['name'] }}')"
            class="shrink-0 px-3 py-2 rounded-xl text-[12px] font-semibold border transition-all
                {{ $activeCategory === $type['name']
                    ? 'bg-[#ff9100] text-white border-[#ff9100] shadow-md'
                    : 'bg-white text-[#7a5d48] border-[#e8d5c4]' }}">
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
            wireId: $wire.__instance.id
        })"
        class="relative shrink-0 flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl border transition-all
            {{ count($selectedTypes) > 0 || count($selectedCategories) > 0
                ? 'bg-[#ff9100] text-white border-[#ff9100] shadow-md'
                : 'bg-white text-[#ff9100] border-[#ff9100]/40' }}">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <span class="text-[10px] font-bold leading-none">Filter</span>
        @if(count($selectedTypes) > 0 || count($selectedCategories) > 0)
        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[#3d2b1f] text-white text-[9px] font-black rounded-full flex items-center justify-center">
            {{ count($selectedTypes) + count($selectedCategories) }}
        </span>
        @endif
    </button>
</div>
