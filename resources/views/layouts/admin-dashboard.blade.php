@extends('layouts.master')

@section('hideFooter')
@endsection

@section('content')
@include('layouts.sidebar')
<div class="min-h-screen bg-gray-50 md:pl-64 pt-16 transition-all duration-300">
    <main class="p-6">
        @yield('dashboard-content')
    </main>
</div>
@endsection
