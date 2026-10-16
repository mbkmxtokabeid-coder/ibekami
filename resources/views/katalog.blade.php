@extends('layouts.app')

@section('title', 'Katalog Plakat Akrilik, Suvenir & Cetak Digital Medan | IBEKAMI')
@section('canonical', 'https://ibekami.id/katalog')
@section('meta_description', 'Katalog lengkap plakat akrilik di Medan, suvenir di Medan, cetak kartu nama, spanduk banner, stiker, hingga tas bingkisan. Pesan cepat satuan maupun grosir.')
@section('meta_keywords', 'katalog plakat akrilik di medan, suvenir di medan, souvenir di medan, aneka produk cetak digital di medan, cetak kartu nama di medan, cetak spanduk & banner di medan, cetak stiker di medan, cetak tas bingkisan di medan, paket suvenir perusahaan di medan, tumbler grafir di medan')
@section('og_image', asset('storage/banners/e840d922-8a26-4f10-98a3-80f53fb62364.webp'))

@section('content')

{{-- Structured Data: BreadcrumbList halaman Katalog --}}
@php
    $katalogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => config('app.url', 'https://ibekami.id')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Katalog Produk Cetak & Suvenir Medan', 'item' => route('katalog')],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($katalogSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>

<div class="min-h-screen bg-[#fff2e0] dark:bg-[#130D08] pt-24 lg:pt-28 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-3 pb-12 sm:pt-4 sm:pb-14">

        {{-- Header: judul + subtitle --}}
        <div class="text-center mb-6 sm:mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C1A0E] dark:text-[#FDF5EC] leading-tight tracking-tight">
                {{ __('messages.product_catalog') }}
            </h1>
            <p class="text-[13px] sm:text-sm text-[#886852] dark:text-[#9E8B7D] mt-1.5 max-w-xl mx-auto">
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
<livewire:footer />

@push('scripts')
<script>
    // Sinkronisasi state katalog saat pengguna menavigasi riwayat browser (Back/Forward)
    window.addEventListener('popstate', function() {
        window.location.reload();
    });
</script>
@endpush

@endsection
