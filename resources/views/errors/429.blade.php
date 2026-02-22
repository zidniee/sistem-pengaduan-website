@extends('errors.layout')

@section('code', '429')
@section('title', 'Terlalu Banyak Permintaan')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2"/>
</svg>
@endsection

@section('message', 'Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.')

@section('additional_info')
<strong>Tips:</strong> Untuk keamanan, sistem membatasi jumlah permintaan per periode waktu. Silakan tunggu 1-2 menit sebelum mencoba kembali.
@endsection
