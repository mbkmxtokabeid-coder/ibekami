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

    {{-- Ulasan — render langsung --}}
    <livewire:halaman-utama.ulasan />

    {{-- Mitra — render langsung --}}
    <livewire:halaman-utama.mitra />

    {{-- Footer — render langsung --}}
    <livewire:footer />

@endsection