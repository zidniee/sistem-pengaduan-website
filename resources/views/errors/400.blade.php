@extends('errors.layout')

@section('code', '400')
@section('title', 'Permintaan Tidak Valid')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message', 'Permintaan yang Anda kirim tidak dapat diproses. Data yang dikirimkan mungkin tidak valid atau tidak lengkap.')

@section('additional_info')
<strong>Tips:</strong> Pastikan semua form telah diisi dengan benar dan coba lagi. Jika masalah berlanjut, hubungi administrator.
@endsection
