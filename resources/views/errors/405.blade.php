@extends('errors.layout')

@section('code', '405')
@section('title', 'Metode Tidak Diizinkan')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
</svg>
@endsection

@section('message', 'Metode HTTP yang digunakan tidak diizinkan untuk endpoint ini. Silakan periksa kembali permintaan Anda.')

@section('additional_info')
<strong>Info:</strong> Ini biasanya terjadi ketika menggunakan metode yang salah (GET, POST, PUT, DELETE) pada suatu endpoint.
@endsection
