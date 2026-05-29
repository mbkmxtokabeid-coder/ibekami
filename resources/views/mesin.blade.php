@extends('layouts.app')

@section('title', 'Mesin Produksi - IBEKAMI')
@section('meta_description', 'Lihat fasilitas mesin produksi digital printing modern dan presisi tinggi milik IBEKAMI di Medan untuk menjamin kualitas cetak terbaik.')

@section('content')

    {{-- Hero mesin — above the fold, render langsung --}}
    <livewire:mesin.hero-mesin />

    {{-- Mesin Section — lazy --}}
    <livewire:mesin.mesin-section lazy />

    {{-- Footer — lazy --}}
    <livewire:footer lazy />

@endsection