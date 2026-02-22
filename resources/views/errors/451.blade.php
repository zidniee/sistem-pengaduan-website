@extends('errors.layout')

@section('code', '451')
@section('title', 'Tidak Tersedia Karena Alasan Hukum')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m0 4h.01"/>
</svg>
@endsection

@section('message', 'Konten ini tidak dapat diakses karena alasan hukum atau kebijakan regulasi yang berlaku.')

@section('additional_info')
<strong>Catatan:</strong> Resource ini telah dibatasi akses sesuai dengan peraturan perundang-undangan yang berlaku di wilayah Anda. Untuk informasi lebih lanjut, silakan hubungi administrator sistem.
@endsection
