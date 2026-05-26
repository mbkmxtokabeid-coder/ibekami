@extends('layouts.app')

@section('title', 'Detail Produk - IBEKAMI')

@section('content')

    <livewire:katalog.detail-katalog :slug="$slug" />

    <livewire:footer lazy />

@endsection
