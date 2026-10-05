@extends('layouts.app')

@section('title', '404 - ' . __('messages.error_404_title') . ' | IBEKAMI')
@section('meta_description', __('messages.error_404_desc'))
@section('robots', 'noindex, follow')

@section('content')
<div class="min-h-[calc(100vh-4.5rem)] sm:min-h-screen bg-[#fff2e0] dark:bg-[#130D08] pt-24 sm:pt-28 pb-12 sm:pb-16 flex flex-col justify-center items-center px-4 relative overflow-hidden transition-colors duration-300">
    
    <div class="max-w-md w-full relative z-10">
        
        {{-- Minimalist Card Container --}}
        <div class="bg-white dark:bg-[#1E140E] rounded-[32px] p-8 shadow-[0_20px_50px_rgba(166,78,47,0.12)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-[#ff9100]/10 dark:border-[#ff9100]/20 text-center">
            
            {{-- Minimalist Icon --}}
            <div class="w-20 h-20 mx-auto mb-6 bg-[#ff9100]/10 dark:bg-[#ff9100]/15 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-[#ff9100]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            {{-- Error Code Badge --}}
            <div class="mb-4">
                <span class="inline-block px-4 py-1.5 bg-[#ff9100]/10 dark:bg-[#ff9100]/15 text-[#ff9100] text-xs font-bold uppercase tracking-wider rounded-full border border-[#ff9100]/20">
                    Error 404
                </span>
            </div>

            {{-- Title --}}
            <h1 class="font-['Playfair_Display',serif] text-2xl sm:text-3xl font-bold text-[#2C1A0E] dark:text-[#FDF5EC] mb-3">
                {{ __('messages.error_404_title') }}
            </h1>

            {{-- Message Description --}}
            <p class="text-[#7a6452] dark:text-[#B59D89] text-[15px] leading-relaxed mb-8">
                @if(isset($exception) && !empty($exception->getMessage()))
                    {{ $exception->getMessage() }}
                @else
                    {{ __('messages.error_404_desc') }}
                @endif
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- 1. Kembali ke Beranda --}}
                <a href="{{ route('home') }}" 
                   id="btn-back-home"
                   class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 py-3.5 px-5 bg-[#ff9100] text-white rounded-xl font-bold hover:bg-[#e68200] transition-all shadow-md">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>{{ __('messages.back_to_home') }}</span>
                </a>

                {{-- 2. Halaman Sebelumnya --}}
                <button type="button" 
                        id="btn-back-previous"
                        onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'"
                        class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 py-3.5 px-5 bg-white dark:bg-[#1E140E] border-2 border-[#ff9100]/30 text-[#ff9100] rounded-xl font-bold hover:bg-[#fff2e0] dark:hover:bg-[#ff9100]/10 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>{{ __('messages.back_to_previous') }}</span>
                </button>
            </div>

        </div>

        {{-- Subtle Static Copyright Note --}}
        <div class="mt-6 text-center text-xs text-[#8A6A54] dark:text-[#9E8B7D]">
            © {{ date('Y') }} IBEKAMI • {{ __('messages.all_rights_reserved') }}
        </div>

    </div>
</div>

@endsection
