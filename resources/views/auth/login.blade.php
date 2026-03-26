@extends('layouts.master')
@section('hideNavbar')
@endsection
@section('hideFooter')
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center relative bg-white overflow-hidden">
    
    <div class="absolute inset-0 z-0 pointer-events-none opacity-10" 
         style="background-image: url('{{ asset('images/Final-Pattern.png') }}'); background-repeat: repeat; background-size: 700px auto;">
    </div>

    <div class="absolute top-0 right-0 w-96 h-96 bg-[#2E86AB]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-[#F59E0B]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">
            
            <div class="h-1.5 w-full bg-gradient-to-r from-[#2E86AB] via-[#2E86AB] to-[#F59E0B]"></div>

            <div class="p-8 sm:p-10">
                <div class="text-center mb-8">
                    <a href="{{ route('homepage') }}">
                        <img src="{{ asset('images/diskominfosp-black.png') }}" 
                             alt="Logo Diskominfo SP Kota Surakarta" 
                             class="h-12 w-auto mx-auto mb-4 drop-shadow-md">
                    </a>
                    
                    <h2 class="text-2xl font-bold text-[#0f172a]">Selamat Datang</h2>
                    <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk melanjutkan</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3 text-sm text-emerald-700">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#2E86AB]/50 focus:border-[#2E86AB] transition-all placeholder:text-slate-400 @error('email') border-red-500 focus:ring-red-200 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>

                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                                class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#2E86AB]/50 focus:border-[#2E86AB] transition-all placeholder:text-slate-400 @error('password') border-red-500 focus:ring-red-200 @enderror">

                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                <svg id="eyeOpen" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeClosed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.27-3.592m3.24-2.213A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.104 5.132M15 12a3 3 0 00-4.75-2.455M9.88 9.88l4.24 4.24M3 3l18 18"></path>
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="rounded border-slate-300 text-[#2E86AB] shadow-sm focus:ring-[#2E86AB]">
                            <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#2E86AB] hover:text-[#0f172a] transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-[#0f172a] hover:bg-[#1e293b] text-white font-bold py-3 px-4 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-[#0f172a]">
                        Masuk Sekarang
                    </button>
                </form>
            </div>

            @if (Route::has('register'))
                <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-600">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-[#2E86AB] hover:text-[#0f172a] font-bold transition-colors ml-1">
                            Daftar Disini
                        </a>
                    </p>
                </div>
            @endif
        </div>
        
        <div class="text-center mt-8 text-slate-400 text-xs">
            &copy; {{ date('Y') }} Diskominfo SP Kota Surakarta
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePassword');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        // Toggle icon visibility
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
    });
</script>
@endsection