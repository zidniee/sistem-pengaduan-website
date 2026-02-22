@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Ditolak')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
</svg>
@endsection

@section('message', 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.')

@section('additional_info')
<strong>Catatan:</strong> Halaman ini hanya dapat diakses oleh pengguna dengan role tertentu. Pastikan Anda sudah login dengan akun yang sesuai.
@endsection