@extends('layouts.app')

@section('title', 'IBEKAMI - Percetakan & Souvenir Kreatif Terbaik Medan')
@section('meta_description', 'IBEKAMI - Percetakan dan souvenir kreatif terbaik di Medan. Melayani plakat, digital printing, dan merchandise custom dengan kualitas premium.')

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