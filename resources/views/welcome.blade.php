@extends('layouts.app')

@section('title', 'Percetakan Express Terdekat di Medan | Souvenir Custom Satuan')
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

    {{-- Footer — render lazily --}}
    <livewire:footer lazy />

@endsection