@extends('layouts.app')

@section('title', 'Mesin Produksi - IBEKAMI')

@section('content')

    {{-- Hero mesin --}}
    <livewire:mesin.hero-mesin />

    {{-- Hot Deals --}}
    <livewire:mesin.mesin-section />

    {{-- footer --}}
    <livewire:footer />

@endsection