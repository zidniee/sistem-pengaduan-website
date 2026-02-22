@extends('layouts.master')
@section('hideFooter')
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('layouts.sidebar-user')
    
    <div class="md:pl-64 pt-16 transition-all duration-300">
        <main class="p-6">
            @yield('user-dashboard-content')
        </main>
    </div>
</div>
@endsection
