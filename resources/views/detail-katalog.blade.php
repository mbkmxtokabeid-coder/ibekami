@extends('layouts.app')

@section('title', $title ?? 'Detail Produk - IBEKAMI')
@section('meta_description', $meta_description ?? 'IBEKAMI - Solusi ekonomi kreatif, merchandise, dan printing terbaik di Medan. Wujudkan ide kreatif Anda bersama kami.')

@section('content')

    <livewire:katalog.detail-katalog :slug="$slug" />

    <livewire:footer />

@endsection
