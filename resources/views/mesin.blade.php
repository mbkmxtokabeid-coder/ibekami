@extends('layouts.app')

@section('title', 'Mesin Produksi - IBEKAMI')

@section('content')

    {{-- Hero mesin — above the fold, render langsung --}}
    <livewire:mesin.hero-mesin />

    {{-- Mesin Section — lazy --}}
    <livewire:mesin.mesin-section lazy />

    {{-- Footer — lazy --}}
    <livewire:footer lazy />

@endsection