@extends('layouts.app')

@section('title', 'Katalog Produk - IBEKAMI')

@section('content')

{{-- Structured Data: BreadcrumbList halaman Katalog --}}
@if(config('app.env') === 'production')
@php
    $katalogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => config('app.url')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Katalog Produk', 'item' => route('katalog')],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($katalogSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endif

<div class="min-h-screen bg-[#fff2e0] pt-24 lg:pt-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header: judul + subtitle — desktop only --}}
        <div class="hidden lg:block mb-8">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-[#2C1A0E] leading-tight">
                {{ __('messages.product_catalog') }}
            </h1>
            <p class="text-[13px] text-[#8A6A54] mt-1">
                {{ __('messages.catalog_subtitle') }}
            </p>
        </div>

        {{-- Layout: Sidebar + Grid --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- Sidebar — hidden di mobile, tampil di desktop --}}
            <div class="hidden lg:block lg:w-auto">
                <livewire:katalog.sidebar-katalog />
            </div>

            {{-- Produk — di mobile tampil ATAS (order-1), di desktop tampil KANAN (order-none) --}}
            <div class="w-full order-1 lg:order-none min-w-0">

                {{-- Mobile filter bar — chips + tombol Filter, di atas product grid, desktop hidden --}}
                <div class="lg:hidden mb-4">
                    <livewire:katalog.mobile-filter-bar />
                </div>

                <livewire:katalog.katalog-section />
            </div>

        </div>
    </div>
</div>

{{-- Footer --}}
<livewire:footer lazy />

@endsection
