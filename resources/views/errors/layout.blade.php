@extends('layouts.master')

@section('hideNavbar', true)
@section('hideFooter', true)

@section('content')
<section class="relative w-full min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-white to-slate-100 overflow-hidden px-4 sm:px-6">

    {{-- Dekorasi background --}}
    <div class="hidden md:block absolute left-10 bottom-20 w-96 h-96 bg-[#2E86AB]/10 rounded-full blur-3xl"></div>
    <div class="hidden md:block absolute right-10 top-20 w-96 h-96 bg-[#F59E0B]/10 rounded-full blur-3xl"></div>
    
    {{-- Decorative shapes --}}
    <div class="hidden lg:block absolute left-32 top-32 w-20 h-20 border-4 border-[#2E86AB]/20 rounded-lg rotate-12"></div>
    <div class="hidden lg:block absolute right-40 bottom-40 w-16 h-16 border-4 border-[#F59E0B]/20 rounded-full"></div>

    <div class="relative z-10 text-center max-w-xl sm:max-w-2xl">

        {{-- Error Icon & Code --}}
        <div class="flex items-center justify-center gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="relative">
                <div class="absolute inset-0 bg-[#2E86AB]/20 rounded-full blur-xl"></div>
                <div class="relative bg-white rounded-full p-4 sm:p-6 shadow-xl border-4 border-[#2E86AB]/30">
                    @yield('icon', 
                        '<svg class="w-12 h-12 sm:w-16 sm:h-16 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                        </svg>'
                    )
                </div>
            </div>
            
            <h1 class="text-7xl sm:text-8xl md:text-9xl lg:text-[10rem] font-black text-transparent bg-clip-text bg-gradient-to-br from-[#2E86AB] to-[#1D5A7A] leading-none drop-shadow-lg">
                @yield('code')
            </h1>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-800 mb-4">
            @yield('title')
        </h2>

        {{-- Description --}}
        <p class="text-base sm:text-lg text-slate-600 mb-8 sm:mb-10 leading-relaxed max-w-lg mx-auto">
            @yield('message')
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('homepage') }}"
               class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 bg-[#2E86AB] text-white rounded-xl font-semibold text-base sm:text-lg hover:bg-[#246b8a] transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
            
            @if(!request()->is('/'))
            <button onclick="history.back()" 
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 bg-white text-[#2E86AB] border-2 border-[#2E86AB] rounded-xl font-semibold text-base sm:text-lg hover:bg-[#2E86AB] hover:text-white transition-all duration-300 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </button>
            @endif
        </div>
        
        {{-- Additional Info --}}
        @hasSection('additional_info')
        <div class="mt-8 sm:mt-10 p-4 sm:p-6 bg-white/80 backdrop-blur-sm rounded-xl border border-slate-200 shadow-md">
            <p class="text-sm text-slate-600">
                @yield('additional_info')
            </p>
        </div>
        @endif

    </div>
</section>
@endsection
