@extends('layouts.app')

@section('title', 'Percetakan Express Terdekat di Medan | Souvenir Custom Murah')
@section('meta_description', 'Percetakan & produsen souvenir custom terdekat di Medan. Proses cetak cepat & express partai besar/grosiran dengan mesin sendiri, harga tetap terjangkau.')
@section('og_image', asset('storage/banners/ac0534a0-7e84-41ae-805f-8bb46f52a951.webp'))

@section('content')

    {{-- Hero mesin — above the fold, render langsung --}}
    <livewire:mesin.hero-mesin />

    {{-- Mesin Section — lazy --}}
    <livewire:mesin.mesin-section lazy />

    {{-- Footer — lazy --}}
    <livewire:footer lazy />

@endsection