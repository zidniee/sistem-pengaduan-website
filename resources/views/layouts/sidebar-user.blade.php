{{-- Mobile Backdrop --}}
<div id="sidebar-backdrop" 
     onclick="toggleSidebar()" 
     class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0 backdrop-blur-sm md:hidden">
</div>

<aside id="sidebar" class="fixed left-0 top-16 h-screen w-64 bg-[#0f172a] border-r border-white/10 shadow-xl pt-6 overflow-y-auto z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <nav class="px-4 py-2 space-y-1">
        
        <div class="px-4 mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
            Menu Utama
        </div>

        <a href="{{ route('user.dashboard') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('user.dashboard') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('user.dashboard') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-sm font-medium">Dashboard</span>
            
            @if(request()->routeIs('user.dashboard'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>

        <a href="{{ route('user.history') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('user.history') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('user.history') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-sm font-medium">Riwayat Aduan</span>
            
            @if(request()->routeIs('user.history'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>

        <div class="my-4 border-t border-white/5 mx-4"></div>

        <div class="px-4 mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
            Pengaturan
        </div>

        <a href="{{ route('profile.edit') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('profile.edit') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('profile.edit') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-sm font-medium">Profil Saya</span>
            
            @if(request()->routeIs('profile.edit'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>

    </nav>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const body = document.body;

        if (sidebar.classList.contains('-translate-x-full')) {
            // Open sidebar
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.remove('opacity-0'), 10); // Fade in
            body.style.overflow = 'hidden'; // Prevent scrolling
        } else {
            // Close sidebar
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => backdrop.classList.add('hidden'), 300); // Wait for fade out
            body.style.overflow = ''; // Restore scrolling
        }
    }

    // Connect navbar toggle button
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('sidebar-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }
        
        // Close sidebar on route change (for SPAs, though Blade is MPA, good practice)
        window.addEventListener('resize', () => {
             if (window.innerWidth >= 768) {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (!sidebar.classList.contains('-translate-x-full')) {
                     // Ensure sidebar is reset on desktop
                     sidebar.classList.add('-translate-x-full'); // Actually on desktop md:translate-x-0 takes over, but this class is for mobile state
                     backdrop.classList.add('hidden', 'opacity-0');
                     document.body.style.overflow = '';
                }
             }
        });
    });
</script>