<section class="relative bg-[#fff2e0] dark:bg-[#130D08] overflow-hidden transition-colors duration-200">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-[#b35200] opacity-[0.08] rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-[#b35200] opacity-[0.1] rounded-full blur-[120px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-6 sm:pt-24 sm:pb-8 md:pt-28 md:pb-10 relative z-10">
        <div class="text-center">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 sm:gap-3 text-[10px] sm:text-[11px] font-black text-[#b35200] dark:text-[#ff9100] uppercase tracking-[0.15em] sm:tracking-[0.2em] mb-3 sm:mb-4 bg-white dark:bg-[#231811] px-3.5 py-1.5 rounded-full shadow-sm border border-[#b35200]/20 dark:border-[#ff9100]/20">
                <span class="w-4 sm:w-6 h-[2px] bg-[#b35200] dark:bg-[#ff9100] rounded-full"></span>
                {{ __('messages.our_technology') }}
                <span class="w-4 sm:w-6 h-[2px] bg-[#b35200] dark:bg-[#ff9100] rounded-full"></span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#3d2b1f] dark:text-[#FDF5EC] leading-tight mb-3 sm:mb-4">
                {{ __('messages.hero_machine_title_prefix') }} <span class="relative">
                    <em class="italic text-[#b35200] dark:text-[#ff9100] not-italic">{{ __('messages.hero_machine_title_highlight') }}</em>
                    <span class="absolute bottom-1 sm:bottom-2 left-0 w-full h-2 sm:h-3 bg-[#b35200]/10 dark:bg-[#ff9100]/10 -z-10"></span>
                </span> {{ __('messages.hero_machine_title_suffix') }}
            </h1>

            {{-- Description --}}
            <div class="max-w-4xl mx-auto px-2">
                <p class="text-[#7a6452] dark:text-[#D8C6B6] text-xs sm:text-base md:text-lg leading-relaxed font-medium">
                    {{ __('messages.hero_machine_desc') }} <span class="text-[#b35200] dark:text-[#ff9100] font-bold">{{ __('messages.precision') }}</span> {{ __('messages.and') }} <span class="text-[#b35200] dark:text-[#ff9100] font-bold">{{ __('messages.consistency') }}</span>.
                </p>
            </div>

            {{-- Decorative Divider --}}
            <div class="mt-6 sm:mt-8 flex justify-center items-center gap-3 sm:gap-4">
                <div class="h-[1px] w-8 sm:w-12 bg-gradient-to-r from-transparent to-[#b35200]/30"></div>
                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#b35200]/20"></div>
                <div class="h-[1px] w-8 sm:w-12 bg-gradient-to-l from-transparent to-[#b35200]/30"></div>
            </div>
        </div>
    </div>

    {{-- Overlay Pattern --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
         style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
    </div>
</section>
