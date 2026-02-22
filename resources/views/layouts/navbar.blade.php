<nav class="fixed top-0 w-full z-900 bg-[#2E86AB]/95 backdrop-blur-md shadow-md border-b border-white/10 transition-all duration-300">
    <div class="w-full px-4 md:px-8 lg:px-12">
        <div class="flex items-center justify-between h-16">

            {{-- 1. LOGO & MENU KIRI --}}
            <div class="flex items-center gap-4 md:gap-8">
                @auth
                {{-- Mobile Sidebar Toggle --}}
                <button id="sidebar-toggle" class="md:hidden text-white p-2 hover:bg-white/10 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </button>
                @endauth
                <a href="/" class="flex items-center gap-3 group shrink-0">
                    <img src="{{ asset('images/diskominfosp.png') }}" 
                         alt="Logo Pemkot" 
                         fetchpriority="high" loading="eager"
                         class="h-8 w-auto drop-shadow-md transition-transform group-hover:scale-105"> </a>
                
                {{-- Separator (Desktop) --}}
                <div class="hidden md:block h-6 w-px bg-white/20"></div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="/" class="relative px-3 py-1.5 text-white font-medium text-sm transition-colors hover:text-[#F59E0B] group">
                        Beranda
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 bg-[#F59E0B] rounded-full transition-all duration-300 
                            {{ request()->is('/') ? 'w-1/2' : 'w-0 group-hover:w-1/2' }}"></span>
                    </a>
                    
                    <a href="/laporan" class="relative px-3 py-1.5 text-white font-medium text-sm transition-colors hover:text-[#F59E0B] group">
                        Laporan Konten
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 bg-[#F59E0B] rounded-full transition-all duration-300 
                            {{ request()->is('laporan*') ? 'w-1/2' : 'w-0 group-hover:w-1/2' }}"></span>
                    </a>
                </div>
            </div>

            {{-- 2. KANAN (Action Buttons) --}}
            <div class="flex items-center gap-3">
                @auth
                {{-- Tombol Lacak (Ukuran diperkecil py-1.5) --}}
                <button onclick="openLacakLaporanModal()" class="hidden lg:flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-white border border-white/20 rounded-full hover:bg-white/10 transition-all hover:border-[#F59E0B]/50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Lacak Aduan
                </button>
                    {{-- User Dropdown (Compact) --}}
                    <div class="relative hidden md:block group py-2 z-50"> 
                        <button class="flex items-center gap-2 pl-4 pr-3 py-1.5 bg-[#0f172a] hover:bg-[#1e293b] text-white text-xs font-bold rounded-full transition-all shadow-sm border border-white/5">
                            <span>{{ Str::limit(Auth::user()->name, 15) }}</span> <svg class="w-3 h-3 text-slate-400 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute right-0 top-full mt-1 w-56 bg-white rounded-lg shadow-xl border border-slate-100 overflow-hidden 
                                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 translate-y-2 origin-top-right">
                            
                            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                                <p class="text-[10px] font-bold text-[#2E86AB] uppercase tracking-wider mb-0.5">Akun Saya</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1">
                                @if(Auth::user()->role === 'operator' || Auth::user()->role === 'admin')
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-[#2E86AB]/5 hover:text-[#2E86AB] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        Dashboard Admin
                                    </a>
                                @else
                                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-[#2E86AB]/5 hover:text-[#2E86AB] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                        Dashboard Saya
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 hover:bg-[#2E86AB]/5 hover:text-[#2E86AB] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Pengaturan Profil
                                    </a>
                                @endif
                            </div>

                            <div class="border-t border-slate-100 p-1 bg-slate-50/50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs text-red-600 hover:bg-red-50 rounded transition-colors font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Guest Buttons (Compact) --}}
                    <div class="hidden md:flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-1.5 bg-[#0f172a] hover:bg-[#1e293b] text-white text-xs font-bold rounded-full shadow-sm transition-all border border-white/5">
                            Masuk
                        </a>
                    </div>
                @endauth

                {{-- Mobile Button --}}
                <button id="mobile-menu-btn" onclick="toggleMenu()" class="md:hidden p-1.5 text-white hover:bg-white/10 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu (Standard) --}}
    <div id="mobile-menu" class="hidden md:hidden">
        <div class="border-t border-white/10 bg-[#2E86AB]">
            <div class="px-4 py-4 space-y-2">
                <a href="/" class="block px-4 py-3 rounded-lg text-white hover:bg-white/10 font-medium text-sm">Beranda</a>
                <a href="/laporan" class="block px-4 py-3 rounded-lg text-white hover:bg-white/10 font-medium text-sm">Laporan Konten</a>
                @auth
                @else
                <a href="{{ route('login') }}" class="block w-full px-4 py-1.5 bg-[#0f172a] hover:bg-[#1e293b] text-white text-xs font-bold rounded-full border border-white/5 text-center">Masuk</a>
                @endauth
            </div>
        </div>
        @auth
        <div class="flex flex-row justify-between px-4 py-3 bg-slate-50 border-b border-slate-100">
            <div>
                <p class="text-[10px] font-bold text-[#2E86AB] uppercase tracking-wider mb-0.5">Akun Saya</p>
                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <div class="relative md:block group py-2 z-50"> 
                    <div class="flex pl-4 pr-3 py-1.5 bg-[#0f172a] text-white text-xs font-bold rounded-full shadow-sm border border-white/5">
                        <span>{{ Str::limit(Auth::user()->name, 15) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 py-1">
            @if(Auth::user()->role === 'operator' || Auth::user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard Admin
                </a>
            @else
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard Saya
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan Profil
                </a>
            @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Lacak Aduan
                </a>
        </div>
        <div class="border-t border-slate-100 p-1 bg-slate-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-xs text-red-600 rounded transition-colors font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
        @endauth
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    const leftSidebar = document.getElementById('left-sidebar');

    if (leftSidebar) {
        leftSidebar.addEventListener('click', (e) => {
            if (e.target.id === 'left-sidebar') {
                toggleSidebar();
            }
        });
    }



</script>