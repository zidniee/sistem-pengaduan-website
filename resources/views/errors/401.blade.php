@extends('errors.layout')

@section('code', '401')
@section('title', 'Autentikasi Diperlukan')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
</svg>
@endsection

@section('message', 'Anda harus login terlebih dahulu untuk mengakses halaman ini. Silakan login dengan akun Anda.')

@section('additional_info')
<strong>Catatan:</strong> Jika Anda sudah login sebelumnya, sesi Anda mungkin telah berakhir. Silakan login kembali.
@endsection
