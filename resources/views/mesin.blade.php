@extends('layouts.app')

@section('title', 'Percetakan Digital di Medan & Workshop Suvenir Modern | IBEKAMI')
@section('canonical', 'https://ibekami.id/mesin')
@section('meta_description', 'Workshop percetakan digital express dan pabrikasi plakat akrilik & suvenir di Medan. Dilengkapi mesin cetak UV flatbed dan laser cutting berpresisi tinggi.')
@section('meta_keywords', 'workshop percetakan digital medan, percetakan digital di medan, mesin cetak digital di medan, laser cutting plakat akrilik di medan, mesin uv flatbed medan, workshop suvenir di medan, cetak stiker medan')
@section('og_image', asset('storage/banners/ac0534a0-7e84-41ae-805f-8bb46f52a951.webp'))

@section('content')

    {{-- Hero mesin — above the fold, render langsung --}}
    <livewire:mesin.hero-mesin />

    {{-- Mesin Section --}}
    <livewire:mesin.mesin-section />

    {{-- Footer --}}
    <livewire:footer />

@endsection