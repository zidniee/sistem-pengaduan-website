@extends('errors.layout')

@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message', 'Maaf, halaman yang Anda cari tidak ditemukan. URL mungkin salah atau halaman telah dipindahkan.')

@section('additional_info')
<strong>Tips:</strong> Periksa kembali URL atau gunakan menu navigasi untuk menemukan halaman yang Anda cari.
@endsection
