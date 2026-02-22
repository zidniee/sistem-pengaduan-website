@extends('errors.layout')

@section('code', '500')
@section('title', 'Terjadi Kesalahan Server')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
</svg>
@endsection

@section('message', 'Maaf, terjadi kesalahan pada server kami. Tim teknis kami telah diberitahu dan sedang menangani masalah ini.')

@section('additional_info')
<strong>Catatan:</strong> Jika masalah ini terus berlanjut, silakan hubungi administrator sistem. Kami mohon maaf atas ketidaknyamanan ini.
@endsection
