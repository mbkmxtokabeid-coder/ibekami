@extends('layouts.app')

@section('content')

    {{-- Hero Section — above the fold, render langsung --}}
    <livewire:halaman-utama.hero />

    {{-- Hot Deals — lazy: render setelah hero selesai --}}
    <livewire:halaman-utama.hot-deals lazy />

    {{-- Product Section — lazy --}}
    <livewire:halaman-utama.product-section lazy />

    {{-- Belanja Online — lazy --}}
    <livewire:halaman-utama.belanja-online lazy />

    {{-- Sosial Media — lazy --}}
    <livewire:halaman-utama.sosial-media lazy />

    {{-- Ulasan — lazy --}}
    <livewire:halaman-utama.ulasan lazy />

    {{-- Mitra — lazy --}}
    <livewire:halaman-utama.mitra lazy />

    {{-- Footer — lazy --}}
    <livewire:footer lazy />

@endsection