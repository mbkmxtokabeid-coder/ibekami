<section class="bg-[#fdfaf7] dark:bg-[#1A120B] pb-12 sm:pb-16 px-3.5 sm:px-6 lg:px-8 font-sans text-slate-800 dark:text-[#D8C6B6] transition-colors duration-200">
    <div class="max-w-7xl mx-auto">
        
        <!-- HEADER SECTION -->
        <div class="pt-6 pb-3 sm:pt-10 sm:pb-6 text-center md:text-left">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-[#FDF5EC] mb-1.5 sm:mb-2">
                {{ __('messages.workshop_capabilities') }} <span class="text-[#ff9100] dark:text-[#b35200]">{{ __('messages.capabilities') }}</span>
            </h2>
            <p class="text-slate-500 dark:text-[#D8C6B6] max-w-2xl text-xs sm:text-base lg:text-lg">
                {{ __('messages.supported_by_latest_tech') }}
            </p>
        </div>

        <!-- GRID SYSTEM -->
        <div class="mt-4 sm:mt-8">
            @if(count($machines) > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
                    @foreach($machines as $machine)
                        <div wire:key="machine-{{ $machine['id'] }}" 
                             class="card-glow group bg-white dark:bg-[#231811] rounded-2xl sm:rounded-3xl lg:rounded-[2.5rem] p-2.5 sm:p-4 lg:p-5 shadow-sm border border-slate-100 dark:border-[#b35200]/20 flex flex-col justify-between transition-all duration-300">
                            
                            {{-- Image Container --}}
                            <div class="aspect-square overflow-hidden rounded-xl sm:rounded-2xl lg:rounded-[2rem] bg-white dark:bg-white mb-2.5 sm:mb-4 relative flex items-center justify-center p-2 sm:p-4 shadow-inner border border-slate-100/80 dark:border-white/10">
                                <img src="{{ $machine['image'] }}" 
                                     alt="{{ $machine['title'] }}"
                                     loading="lazy"
                                     width="400"
                                     height="400"
                                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                                     onerror="this.src='https://via.placeholder.com/400x400?text={{ urlencode($machine['title']) }}'">
                            </div>

                            {{-- Machine Info --}}
                            <div class="px-1 sm:px-2 pb-1 sm:pb-2">
                                <span class="text-[#8B5E3C] dark:text-[#ff9100] font-bold text-[9px] sm:text-[11px] lg:text-xs uppercase tracking-wider sm:tracking-widest block truncate">
                                    {{ __('messages.production_machine') }}
                                </span>
                                <h4 class="text-xs sm:text-base lg:text-lg font-extrabold text-[#2D241E] dark:text-[#FDF5EC] mt-0.5 sm:mt-1 leading-snug line-clamp-2 min-h-[2rem] sm:min-h-[2.5rem] lg:min-h-[2.75rem]" title="{{ $machine['title'] }}">
                                    {{ $machine['title'] }}
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12 sm:py-16">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-300 dark:text-[#9E8B7D]/40 mx-auto mb-3 sm:mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-slate-400 dark:text-[#9E8B7D] font-semibold text-sm sm:text-base lg:text-lg">{{ __('messages.no_machines_added') }}</p>
                </div>
            @endif
        </div>

        <!-- CONTACT STRIP -->
        <div class="mt-8 sm:mt-14 bg-white dark:bg-[#231811] border border-slate-200/80 dark:border-[#b35200]/20 rounded-2xl sm:rounded-3xl lg:rounded-[2.5rem] p-3.5 sm:p-5 lg:p-6 flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6 shadow-xl shadow-slate-200/40 dark:shadow-none">
            <div class="flex items-center gap-3.5 sm:gap-5 w-full md:w-auto">
                <div class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 rounded-xl sm:rounded-2xl bg-[#ff9100] dark:bg-[#b35200] flex items-center justify-center text-white shrink-0 shadow-lg shadow-[#ff9100]/25 dark:shadow-[#b35200]/30">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h5 class="text-sm sm:text-lg lg:text-xl font-bold text-slate-900 dark:text-[#FDF5EC] uppercase tracking-tight truncate">{{ __('messages.start_your_project') }}</h5>
                    <p class="text-slate-400 dark:text-[#9E8B7D] text-xs sm:text-sm italic truncate">{{ __('messages.lets_build_together') }}</p>
                </div>
            </div>
            <a href="https://wa.me/62817076999?text=Halo%20Admin%2C%20saya%20tertarik%20dengan%20produk%20dari%20Ibekami.id.%20Bisa%20bantu%20untuk%20info%20lebih%20lanjut%3F" 
               target="_blank"
               rel="noopener noreferrer"
               @click.throttle.2000ms
               class="w-full md:w-auto bg-[#ff9100] dark:bg-[#b35200] hover:bg-[#e07d00] dark:hover:bg-[#994500] active:scale-[0.98] text-[#130D08] dark:text-white px-5 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-4.5 rounded-xl sm:rounded-2xl lg:rounded-[1.8rem] font-extrabold transition-all duration-300 text-center uppercase tracking-wider sm:tracking-widest text-xs shadow-lg shadow-[#ff9100]/25 dark:shadow-[#b35200]/30 shrink-0">
                {{ __('messages.contact_via_whatsapp') }}
            </a>
        </div>

    </div>
</section>


