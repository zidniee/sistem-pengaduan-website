@extends('errors.layout')

@section('code', '408')
@section('title', 'Request Timeout')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message', 'Permintaan Anda memakan waktu terlalu lama dan telah melebihi batas waktu. Silakan coba lagi.')

@section('additional_info')
<strong>Tips:</strong> Pastikan koneksi internet Anda stabil. Jika upload file, coba file dengan ukuran lebih kecil.
@endsection
