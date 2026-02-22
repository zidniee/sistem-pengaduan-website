@extends('errors.layout')

@section('code', '410')
@section('title', 'Resource Sudah Dihapus')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
</svg>
@endsection

@section('message', 'Resource yang Anda cari sudah dihapus secara permanen dan tidak akan tersedia lagi.')

@section('additional_info')
<strong>Catatan:</strong> Ini adalah penghapusan permanen. Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator untuk informasi lebih lanjut.
@endsection
