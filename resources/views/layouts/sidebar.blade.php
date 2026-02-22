    <!-- Toaster -->
        <div id="toast-container" class="fixed bottom-6 right-6 z-50 space-y-3 pointer-events-none z-[60]">
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                createToast("{{ session('success') }}", 'bg-green-600');
            });
        </script>
    @endif
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                createToast("{{ $errors->first() }}", 'bg-red-600');
            });
        </script>
    @endif
    </div>

{{-- Mobile Backdrop --}}
<div id="sidebar-backdrop" 
     onclick="toggleSidebar()" 
     class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity opacity-0 backdrop-blur-sm md:hidden">
</div>

<aside id="sidebar" class="fixed left-0 top-16 h-[calc(100vh-4rem)] w-64 bg-[#0f172a] border-r border-white/10 shadow-xl pt-6 overflow-y-auto z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <nav class="px-4 py-2 space-y-1">
        
        <div class="px-4 mb-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
            Menu Utama
        </div>

        <a href="{{ route('dashboard') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('dashboard') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('dashboard') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="text-sm font-medium">Dashboard</span>
            
            @if(request()->routeIs('dashboard'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>
        <!-- TODO: MODAL POPUP FOR DATA INPUT/CREATION -->
       <a href="#" id="tambah-button"
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border border-transparent hover:bg-white/5 hover:border-white/5 text-slate-400 hover:text-white">
            <svg class="w-5 h-5 transition-colors group-hover:text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span class="text-sm font-medium">Buat Laporan Baru</span>
        </a>

        <a href="{{ route('lapor-perhari') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('lapor-perhari') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('lapor-perhari') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <!-- TODO: ADD COUNT OF LAPORAN PER DAY -->
            <span class="text-sm font-medium">Laporan Perhari</span>
            @if ($report_count)
            <span class="ml-auto bg-[#2E86AB] text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">{{ $report_count }}</span>
            @endif
        </a>
        <a href="{{ route('laporan.import.form') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('laporan.import.form') || request()->routeIs('laporan.import.history') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('laporan.import.form') || request()->routeIs('laporan.import.history') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <span class="text-sm font-medium">Import Laporan</span>
            
            @if(request()->routeIs('laporan.import.form') || request()->routeIs('laporan.import.history'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>
        <a href="{{ route('complaint-list') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('complaint-list') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('complaint-list') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3"></path>
            </svg>
            <span class="text-sm font-medium">Daftar Laporan</span>
            
            @if(request()->routeIs('complaint-list'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>

        <a href="{{ route('platforms.list') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 border
           {{ request()->routeIs('platforms.list') 
              ? 'bg-[#2E86AB]/10 text-[#F59E0B] border-[#F59E0B]/30 shadow-sm shadow-[#F59E0B]/5' 
              : 'text-slate-400 border-transparent hover:bg-white/5 hover:text-white hover:border-white/5' 
           }}">
            <svg class="w-5 h-5 transition-colors {{ request()->routeIs('platforms.list') ? 'text-[#F59E0B]' : 'group-hover:text-[#2E86AB]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="text-sm font-medium">Tambah Platform</span>
            
            @if(request()->routeIs('platform.list'))
                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#F59E0B]/5 to-transparent pointer-events-none"></div>
            @endif
        </a>
    </nav>
</aside>

@if(session()->has('addSuccess') || session()->has('addError'))
<div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-container">
@else
<div class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" id="modal-container">
@endif
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" id="backdrop"></div>
    <div class="relative bg-white shadow-2xl z-10 w-full md:max-w-[60%] max-h-[90vh] md:max-h-[85%] flex flex-col rounded-xl overflow-auto">
        <form action="{{ route('operatorSubmitComplaints') }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
            @csrf
            <div class="flex-1 overflow-y-auto p-6 md:p-8">
                <div class="flex flex-col xl:flex-row items-start justify-center gap-6 md:space-x-8 w-full">
                    <div class="w-full flex-1">
                        @include('layouts.form.section1')
                    </div>
                    <div class="block md:hidden border-t border-slate-100 w-full my-2"></div>
                    <div class="w-full flex-1">
                        @include('layouts.form.section2')
                    </div>
                </div>
            </div>
            <div class="p-4 md:p-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 bg-slate-50">
                <button type="button" 
                        onclick="document.getElementById('modal-container').classList.add('hidden')" 
                        class="w-full sm:w-auto px-6 py-3 text-slate-600 font-medium hover:text-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-[#0f172a] text-white font-bold rounded-lg hover:bg-[#1e293b] transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById("modal-container");
    const backdrop = document.getElementById("backdrop");

    document.getElementById('tambah-button').addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal || e.target === backdrop) {
            modal.classList.add('hidden');
        }
    });
    
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const body = document.body;

        if (sidebar.classList.contains('-translate-x-full')) {
            // Open sidebar
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            body.style.overflow = 'hidden';
        } else {
            // Close sidebar
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
            body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('sidebar-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }
    });

    function createToast(message, bgColor) {
        const container = document.getElementById('toast-container');
        
        // Create the toast element
        const toast = document.createElement('div');
        toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-xl transition-all duration-500 transform translate-y-10 opacity-0 pointer-events-auto`;
        toast.innerHTML = message;

        // Add to container
        container.appendChild(toast);

        // Animate In
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        }, 100);

        // Auto-remove after 4 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }
</script>