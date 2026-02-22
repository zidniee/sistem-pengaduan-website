@extends('errors.layout')

@section('code', '504')
@section('title', 'Gateway Timeout')

@section('icon')
<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
</svg>
@endsection

@section('message', 'Server gateway tidak menerima respons tepat waktu dari server upstream. Silakan coba lagi beberapa saat.')

@section('additional_info')
<strong>Catatan:</strong> Ini biasanya masalah sementara akibat beban server yang tinggi. Silakan tunggu sebentar dan coba kembali.
@endsection
