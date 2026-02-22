@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Berakhir')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('message', 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan muat ulang halaman dan coba lagi.')

@section('additional_info')
<strong>Tips:</strong> Untuk keamanan, sesi akan otomatis berakhir setelah beberapa waktu tidak aktif. Silakan refresh halaman (F5) dan lakukan kembali tindakan Anda.
@endsection
