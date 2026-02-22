@extends('layouts.master')
@section('hideNavbar')
@endsection
@section('hideFooter')
@endsection

@section('content')
<div class="min-h-screen flex items-center justify-center relative bg-white overflow-hidden py-8">
    
    <div class="absolute inset-0 z-0 pointer-events-none opacity-10" 
         style="background-image: url('{{ asset('images/Final-Pattern.png') }}'); background-repeat: repeat; background-size: 700px auto;">
    </div>

    <div class="absolute top-0 left-0 w-96 h-96 bg-[#2E86AB]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-[#F59E0B]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">
            
            <div class="h-1.5 w-full bg-gradient-to-r from-[#2E86AB] via-[#2E86AB] to-[#F59E0B]"></div>

            <div class="p-8">
                <div class="text-center mb-8">
                    <img src="{{ asset('images/diskominfosp-black.png') }}" 
                         alt="Logo Diskominfo SP Kota Surakarta" 
                         class="h-12 w-auto mx-auto mb-4 drop-shadow-md">
                    
                    <h2 class="text-2xl font-bold text-[#0f172a]">Buat Akun Baru</h2>
                    <p class="text-slate-500 text-sm mt-1">Lengkapi data untuk mendaftar</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Anda"
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#2E86AB]/50 focus:border-[#2E86AB] transition-all placeholder:text-slate-400 @error('name') border-red-500 focus:ring-red-200 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com"
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#2E86AB]/50 focus:border-[#2E86AB] transition-all placeholder:text-slate-400 @error('email') border-red-500 focus:ring-red-200 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter"
                                class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#2E86AB]/50 focus:border-[#2E86AB] transition-all placeholder:text-slate-400 @error('password') border-red-500 focus:ring-red-200 @enderror">
                            <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors" data-target="password">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password"
                                class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#2E86AB]/50 focus:border-[#2E86AB] transition-all placeholder:text-slate-400">
                            <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors" data-target="password_confirmation">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#0f172a] hover:bg-[#1e293b] text-white font-bold py-3 px-4 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-[#0f172a]">
                        Daftar Sekarang
                    </button>

                    <script>
                        document.querySelectorAll('.toggle-password').forEach(button => {
                            button.addEventListener('click', function(e) {
                                e.preventDefault();
                                const inputId = this.getAttribute('data-target');
                                const input = document.getElementById(inputId);
                                const eyeOpen = this.querySelector('.eye-open');
                                const eyeClosed = this.querySelector('.eye-closed');
                                
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    eyeOpen.classList.add('hidden');
                                    eyeClosed.classList.remove('hidden');
                                } else {
                                    input.type = 'password';
                                    eyeOpen.classList.remove('hidden');
                                    eyeClosed.classList.add('hidden');
                                }
                            });
                        });
                    </script>

                </form>
            </div>

            <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-[#2E86AB] hover:text-[#0f172a] font-bold transition-colors ml-1">
                        Masuk Disini
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center mt-8 text-slate-400 text-xs">
            &copy; {{ date('Y') }} Diskominfo SP Kota Surakarta
        </div>
    </div>
</div>
@endsection