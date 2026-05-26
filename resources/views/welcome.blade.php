@extends('layouts.app')

@section('content')

    {{-- Hero Section — above the fold, render langsung --}}
    <livewire:halaman-utama.hero />

    {{-- Hot Deals — render langsung --}}
    <livewire:halaman-utama.hot-deals />

    {{-- Product Section — render langsung --}}
    <livewire:halaman-utama.product-section />

    {{-- Sosial Media — render langsung --}}
    <livewire:halaman-utama.sosial-media />

    {{-- Ulasan — lazy loaded --}}
    <livewire:halaman-utama.ulasan lazy />

    {{-- Mitra — lazy loaded --}}
    <livewire:halaman-utama.mitra lazy />

    {{-- Footer — lazy loaded --}}
    <livewire:footer lazy />

@endsection