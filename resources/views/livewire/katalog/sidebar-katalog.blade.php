{{-- Wrapper tunggal — Livewire hanya boleh punya satu root element --}}
<div style="isolation: isolate;">

<aside class="w-full lg:w-80 shrink-0">
    <div class="lg:sticky lg:top-[76px] space-y-6">

        {{-- Main Sidebar Frame --}}
        <div class="bg-[#fff2e0] rounded-[40px] p-8 space-y-8 shadow-[0_20px_50px_rgba(166,78,47,0.15),0_10px_20px_rgba(0,0,0,0.05)] border border-white/50">

            {{-- Search Section — desktop only --}}
            <div class="hidden lg:block">
                <p class="text-[11px] font-black tracking-[0.15em] uppercase text-[#ff9100] mb-4 ml-1">{{ __('messages.search_products') }}</p>
                <div class="relative group">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/90 z-10"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        wire:model.live.debounce.400ms="search"
                        type="text"
                        placeholder="{{ __('messages.search_products_placeholder') }}"
                        class="w-full bg-[#ff9100] text-white placeholder-white/70 text-[13px] font-medium
                               pl-12 pr-4 py-4 rounded-2xl border-none outline-none
                               shadow-[0_10px_20px_-5px_rgba(255,145,0,0.4)] focus:ring-4 focus:ring-[#ff9100]/20 transition-all"
                    >
                </div>
            </div>

            {{-- Kategori Section — desktop only, di mobile pakai popup filter --}}
            <div class="hidden lg:block" x-data="{
                open: true,
                debounceTimer: null,
                debouncedSetCategory(categoryName) {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => {
                        $wire.setCategory(categoryName);
                    }, 400);
                }
            }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between mb-4 ml-1 outline-none group">
                    <p class="text-[11px] font-black tracking-[0.15em] uppercase text-[#7a5d48]">{{ __('messages.category') }}</p>
                    <svg class="w-4 h-4 text-[#ff9100] transition-transform duration-300 mr-1"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="space-y-1">

                    @foreach($categories as $cat)
                        @if($cat['group'] === 'all')
                        <button
                            @click="debouncedSetCategory('{{ $cat['name'] }}')"
                            class="w-full flex items-center justify-between px-5 py-3 rounded-2xl text-[14px] font-bold transition-all
                                {{ $activeCategory === $cat['name']
                                    ? 'bg-white text-[#ff9100] shadow-[0_8px_15px_rgba(0,0,0,0.08)] border border-[#ff9100]/10 scale-[1.02]'
                                    : 'text-[#8c7664] hover:bg-white/40 hover:translate-x-1' }}">
                            <span class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0 {{ $activeCategory === $cat['name'] ? 'bg-[#ff9100] shadow-[0_0_8px_#ff9100]' : 'bg-[#d1c2b4]' }}"></span>
                                {{ $cat['name'] }}
                            </span>
                            <span class="text-[11px] font-black px-2.5 py-1 rounded-full {{ $activeCategory === $cat['name'] ? 'bg-[#ff9100]/10 text-[#ff9100]' : 'bg-[#f5ede8] text-[#a89584]' }}">
                                {{ $cat['count'] }}
                            </span>
                        </button>
                        @endif
                    @endforeach

                    @php $types = array_filter($categories, fn($c) => $c['group'] === 'type'); @endphp
                    @if(count($types) > 0)
                    <div x-data="{ openType: true }" class="pt-1">
                        <button @click="openType = !openType"
                            class="w-full flex items-center justify-between px-5 py-2 text-[10px] font-black tracking-[0.12em] uppercase text-[#ff9100] outline-none">
                            <span>Tipe Produk</span>
                            <svg class="w-3 h-3 transition-transform duration-200" :class="openType ? 'rotate-180' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openType" x-transition class="space-y-1 mt-1">
                            @foreach($categories as $cat)
                                @if($cat['group'] === 'type')
                                <button
                                    @click="debouncedSetCategory('{{ $cat['name'] }}')"
                                    class="w-full flex items-center justify-between px-5 py-2.5 rounded-2xl text-[13px] font-semibold transition-all
                                        {{ $activeCategory === $cat['name']
                                            ? 'bg-white text-[#ff9100] shadow-[0_8px_15px_rgba(0,0,0,0.08)] border border-[#ff9100]/10 scale-[1.02]'
                                            : 'text-[#8c7664] hover:bg-white/40 hover:translate-x-1' }}">
                                    <span class="flex items-center gap-3 text-left flex-1">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $activeCategory === $cat['name'] ? 'bg-[#ff9100]' : 'bg-[#d1c2b4]' }}"></span>
                                        <span class="leading-tight">{{ $cat['name'] }}</span>
                                    </span>
                                    <span class="text-[11px] font-black px-2 py-0.5 rounded-full {{ $activeCategory === $cat['name'] ? 'bg-[#ff9100]/10 text-[#ff9100]' : 'bg-[#f5ede8] text-[#a89584]' }}">
                                        {{ $cat['count'] }}
                                    </span>
                                </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @php $cats = array_filter($categories, fn($c) => $c['group'] === 'category'); @endphp
                    @if(count($cats) > 0)
                    <div x-data="{ openCat: false }" class="pt-1">
                        <button @click="openCat = !openCat"
                            class="w-full flex items-center justify-between px-5 py-2 text-[10px] font-black tracking-[0.12em] uppercase text-[#7a5d48] outline-none">
                            <span>Kategori</span>
                            <svg class="w-3 h-3 transition-transform duration-200" :class="openCat ? 'rotate-180' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openCat" x-transition class="space-y-1 mt-1">
                            @foreach($categories as $cat)
                                @if($cat['group'] === 'category')
                                <button
                                    @click="debouncedSetCategory('{{ $cat['name'] }}')"
                                    class="w-full flex items-center justify-between px-5 py-2.5 rounded-2xl text-[13px] font-semibold transition-all
                                        {{ $activeCategory === $cat['name']
                                            ? 'bg-white text-[#ff9100] shadow-[0_8px_15px_rgba(0,0,0,0.08)] border border-[#ff9100]/10 scale-[1.02]'
                                            : 'text-[#8c7664] hover:bg-white/40 hover:translate-x-1' }}">
                                    <span class="flex items-center gap-3 text-left flex-1">
                                        <span class="w-2 h-2 rounded-full shrink-0 {{ $activeCategory === $cat['name'] ? 'bg-[#ff9100]' : 'bg-[#d1c2b4]' }}"></span>
                                        <span class="leading-tight">{{ $cat['name'] }}</span>
                                    </span>
                                    <span class="text-[11px] font-black px-2 py-0.5 rounded-full {{ $activeCategory === $cat['name'] ? 'bg-[#ff9100]/10 text-[#ff9100]' : 'bg-[#f5ede8] text-[#a89584]' }}">
                                        {{ $cat['count'] }}
                                    </span>
                                </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Urutkan Section — desktop only --}}
            <div class="hidden lg:block" x-data="{
                debounceTimer: null,
                debouncedSetSort(sortValue) {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => {
                        $wire.setSort(sortValue);
                    }, 400);
                }
            }">
                <p class="text-[11px] font-black tracking-[0.15em] uppercase text-[#7a5d48] mb-4 ml-1">{{ __('messages.sort_by') }}</p>
                <div class="space-y-2">
                    @foreach([__('messages.newest'), __('messages.oldest'), __('messages.name_az'), __('messages.name_za')] as $sort)
                        <button
                            @click="debouncedSetSort('{{ $sort }}')"
                            class="w-full text-left px-5 py-3.5 rounded-2xl text-[14px] font-bold transition-all
                                   {{ $sortBy === $sort
                                        ? 'bg-[#3d2b1f] text-white shadow-[0_10px_20px_rgba(61,43,31,0.3)] scale-[1.02]'
                                        : 'text-[#8c7664] hover:bg-white/40' }}">
                            {{ $sort }}
                        </button>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- WhatsApp CTA Card --}}
        <div class="bg-[#ff9100] rounded-[32px] p-6 text-center shadow-[0_20px_40px_rgba(255,145,0,0.25)] border-t border-white/20">
            <p class="text-white font-black text-[16px] mb-1">{{ __('messages.need_help') }}</p>
            <p class="text-white/80 text-[12px] mb-5 font-medium leading-relaxed">{{ __('messages.free_consultation') }}</p>
            <a href="https://wa.me/62817076999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F"
               target="_blank"
               rel="noopener noreferrer"
               @click.throttle.2000ms
               class="flex items-center justify-center gap-2 bg-white text-[#2C1A0E] font-bold text-[14px] px-5 py-3.5 rounded-xl hover:bg-[#fff2e0] transition-colors shadow-md">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                {{ __('messages.ask_via_whatsapp') }}
            </a>
        </div>

    </div>
</aside>

</div>{{-- end wrapper --}}
