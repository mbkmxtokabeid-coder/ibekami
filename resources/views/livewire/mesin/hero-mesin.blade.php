<section class="relative bg-[#fff2e0] dark:bg-[#130D08] overflow-hidden transition-colors duration-200">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-[#b35200] opacity-[0.08] rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-[#b35200] opacity-[0.1] rounded-full blur-[120px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-6 sm:pt-24 sm:pb-8 md:pt-28 md:pb-10 relative z-10">
        <div class="text-center">
            {{-- Badge --}}
            <div class="flex items-center justify-center gap-3 text-xs sm:text-[13px] font-bold text-[#b35200] dark:text-[#ff9100] uppercase tracking-[0.2em] mb-2 sm:mb-3">
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200] dark:bg-[#ff9100]"></span>
                {{ __('messages.our_technology') }}
                <span class="w-10 sm:w-12 h-[1px] bg-[#b35200] dark:bg-[#ff9100]"></span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-[#3d2b1f] dark:text-[#FDF5EC] leading-tight mb-3 sm:mb-4">
                {{ __('messages.hero_machine_title_prefix') }} <span class="text-[#b35200] dark:text-[#ff9100]">{{ __('messages.hero_machine_title_highlight') }}</span> {{ __('messages.hero_machine_title_suffix') }}
            </h1>

            {{-- Description --}}
            <div class="max-w-4xl mx-auto px-2">
                <p class="text-[#7a6452] dark:text-[#D8C6B6] text-xs sm:text-base md:text-lg leading-relaxed font-medium">
                    {{ __('messages.hero_machine_desc') }} <span class="text-[#b35200] dark:text-[#ff9100] font-bold">{{ __('messages.precision') }}</span> {{ __('messages.and') }} <span class="text-[#b35200] dark:text-[#ff9100] font-bold">{{ __('messages.consistency') }}</span>.
                </p>
            </div>
        </div>
    </div>

    {{-- Overlay Pattern --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
         style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
    </div>
</section>
