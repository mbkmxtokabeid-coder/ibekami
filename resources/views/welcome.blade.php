@extends('layouts.app')

@section('title', 'Percetakan Express Medan | Souvenir Custom Satuan')
@section('canonical', 'https://ibekami.id/')
@section('meta_description', 'Butuh cetak cepat? IBEKAMI adalah percetakan express terdekat di Medan untuk souvenir custom terjangkau. Melayani partai besar, partai kecil, dan satuan.')
@section('og_image', asset('storage/banners/428f232a-c988-4731-8cf7-ceec4874496c.webp'))

@section('content')

    {{-- Hero Section — above the fold, render langsung --}}
    <livewire:halaman-utama.hero />

    {{-- Hot Deals — render lazily --}}
    <livewire:halaman-utama.hot-deals lazy />

    {{-- Product Section — render lazily --}}
    <livewire:halaman-utama.product-section lazy />

    {{-- Sosial Media — render lazily --}}
    <livewire:halaman-utama.sosial-media lazy />

    {{-- Ulasan — render lazily --}}
    <livewire:halaman-utama.ulasan lazy />

    {{-- Mitra — render lazily --}}
    <livewire:halaman-utama.mitra lazy />

    {{-- FAQ & About Section for SEO and Customer Information --}}
    <section id="faq-about" class="py-16 sm:py-20 px-4 bg-[#fdfaf7] relative overflow-hidden">
        {{-- Background blur blobs --}}
        <div class="absolute top-1/2 left-[-10%] w-[30vw] h-[30vw] bg-[#ff9100]/5 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[25vw] h-[25vw] bg-[#ff9100]/5 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start relative z-10">
            {{-- KONTEN KIRI: TENTANG KAMI --}}
            <div class="lg:col-span-5 flex flex-col items-start space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#4a3728]/10 border border-[#4a3728]/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#ff9100] animate-pulse"></span>
                    <span class="text-[10px] font-bold text-[#4a3728] uppercase tracking-widest">{{ __('messages.about_us') }}</span>
                </div>
                
                <h2 class="font-['Playfair_Display'] text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight text-[#2C1A0E] tracking-tight">
                    {{ __('messages.about_title') }}
                </h2>

                <p class="text-[13px] sm:text-[14px] text-[#5C3D28] leading-relaxed opacity-95 text-justify">
                    {{ __('messages.about_desc') }}
                </p>
            </div>

            {{-- KONTEN KANAN: FAQ ACCORDION --}}
            <div class="lg:col-span-7 w-full flex flex-col space-y-4" x-data="{ activeFaq: null }">
                <div class="mb-4">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#ff9100]/10 border border-[#ff9100]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#ff9100]"></span>
                        <span class="text-[10px] font-bold text-[#b35200] uppercase tracking-widest">{{ __('messages.faq_badge') }}</span>
                    </div>
                    <h2 class="font-['Playfair_Display'] text-2xl sm:text-3xl font-black text-[#2C1A0E] tracking-tight mt-3">
                        {{ __('messages.faq_title') }}
                    </h2>
                    <p class="text-[12px] sm:text-[13px] text-[#886852] font-medium mt-2 leading-relaxed">
                        {{ __('messages.faq_subtitle') }}
                    </p>
                </div>

                {{-- Loop 6 FAQ Items --}}
                @foreach(range(1, 6) as $i)
                    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden transition-all duration-300 shadow-sm"
                          :class="activeFaq === {{ $i }} ? 'border-[#b35200]/30 shadow-md shadow-[#b35200]/5' : 'hover:border-[#b35200]/20'">
                        
                        {{-- Question Button --}}
                        <button type="button" 
                                @click="activeFaq = (activeFaq === {{ $i }} ? null : {{ $i }})"
                                class="w-full text-left px-5 py-4 sm:px-6 sm:py-5 flex items-center justify-between gap-4 outline-none focus:outline-none select-none">
                            <span class="text-[13px] sm:text-[14px] font-bold text-[#2C1A0E] transition-colors duration-300"
                                  :class="activeFaq === {{ $i }} ? 'text-[#b35200]' : 'group-hover:text-[#b35200]'">
                                {{ __("messages.faq_q{$i}") }}
                            </span>
                            
                            {{-- Chevron Icon with Rotation --}}
                            <svg class="w-4 h-4 text-[#8A6A54] transition-transform duration-300 shrink-0" 
                                 :class="activeFaq === {{ $i }} ? 'rotate-180 text-[#b35200]' : ''" 
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Answer Container with Transition --}}
                        <div x-show="activeFaq === {{ $i }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-[500px]"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 max-h-[500px]"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="border-t border-[#b35200]/10 bg-gradient-to-b from-[#fdfaf7]/50 to-[#fff2e0]/20"
                             style="display: none;">
                            <div class="px-5 py-4 sm:px-6 sm:py-5 text-[12px] sm:text-[13px] text-[#5C3D28] leading-relaxed">
                                {{ __("messages.faq_a{$i}") }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer — render lazily --}}
    <livewire:footer lazy />

@endsection