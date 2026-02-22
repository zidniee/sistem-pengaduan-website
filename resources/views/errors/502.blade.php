@extends('errors.layout')

@section('code', '502')
@section('title', 'Bad Gateway')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
</svg>
@endsection

@section('message', 'Server gateway mengalami masalah dalam memproses permintaan Anda. Tim teknis kami sedang menangani masalah ini.')

@section('additional_info')
<strong>Catatan:</strong> Ini biasanya merupakan masalah sementara. Silakan coba lagi dalam beberapa saat.
@endsection
