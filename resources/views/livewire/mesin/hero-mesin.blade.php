<section class="relative bg-[#fff2e0] overflow-hidden pt-20 md:pt-0">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-[#ff9100] opacity-[0.08] rounded-full blur-[100px]"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-96 h-96 bg-[#ff9100] opacity-[0.1] rounded-full blur-[120px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-24 relative z-10">
        <div class="text-center">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-3 text-[11px] font-black text-[#ff9100] uppercase tracking-[0.2em] mb-6 bg-white px-4 py-2 rounded-full shadow-sm border border-[#ff9100]/10">
                <span class="w-6 h-[2px] bg-[#ff9100] rounded-full"></span>
                {{ __('messages.our_technology') }}
                <span class="w-6 h-[2px] bg-[#ff9100] rounded-full"></span>
            </div>

            {{-- Title --}}
            <h1 class="font-['Playfair_Display'] text-4xl md:text-7xl font-bold text-[#3d2b1f] leading-tight mb-8">
                {{ __('messages.production_machines') }} <span class="relative">
                    <em class="italic text-[#ff9100] not-italic">{{ __('messages.machines') }}</em>
                    <span class="absolute bottom-2 left-0 w-full h-3 bg-[#ff9100]/10 -z-10"></span>
                </span> {{ __('messages.we_proud_to_serve') }}
            </h1>

            {{-- Description --}}
            <div class="max-w-2xl mx-auto">
                <p class="text-[#7a6452] text-lg md:text-xl leading-relaxed font-medium">
                    {{ __('messages.supported_by_high_tech') }} <span class="text-[#ff9100] font-bold">{{ __('messages.precision_and') }}</span> {{ __('messages.and') }} <span class="text-[#ff9100] font-bold">{{ __('messages.consistency') }}</span>.
                </p>
            </div>

            {{-- Decorative Divider --}}
            <div class="mt-12 flex justify-center items-center gap-4">
                <div class="h-[1px] w-12 bg-gradient-to-r from-transparent to-[#ff9100]/30"></div>
                <div class="w-2 h-2 rounded-full bg-[#ff9100]/20"></div>
                <div class="h-[1px] w-12 bg-gradient-to-l from-transparent to-[#ff9100]/30"></div>
            </div>
        </div>
    </div>

    {{-- Overlay Pattern --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
         style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
    </div>
</section>
