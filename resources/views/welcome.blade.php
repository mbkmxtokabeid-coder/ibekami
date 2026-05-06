@extends('layouts.app')

@section('content')

    {{-- Hero Section --}}
    <livewire:halaman-utama.hero />

    {{-- Hot Deals --}}
    <livewire:halaman-utama.hot-deals />

    {{-- product-section --}}
    <livewire:halaman-utama.product-section />

    {{-- belanja-online --}}
    <livewire:halaman-utama.belanja-online />

    {{-- ulasan --}}
    <livewire:halaman-utama.ulasan />

    {{-- mitra --}}
    <livewire:halaman-utama.mitra />

    {{-- footer --}}
    <livewire:footer />

@endsection