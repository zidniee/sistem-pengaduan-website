@extends('layouts.admin-dashboard')

@section('dashboard-content')

<div id="detail-skeleton" class="animate-pulse space-y-8">
     {{-- Header Skeleton --}}
    <div class="flex justify-between items-center mb-8">
        <div class="space-y-2">
            <div class="h-8 bg-slate-300 rounded w-48"></div>
            <div class="h-4 bg-slate-200 rounded w-64"></div>
        </div>
        <div class="h-10 bg-slate-200 rounded w-24"></div>
    </div>

    {{-- Banner Skeleton --}}
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="h-24 bg-slate-300"></div>
        <div class="p-6 md:p-8 space-y-8">
            {{-- Section 1 --}}
             <div>
                <div class="h-4 bg-slate-200 rounded w-32 mb-4"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="h-12 bg-slate-100 rounded"></div>
                    <div class="h-12 bg-slate-100 rounded"></div>
                     <div class="md:col-span-2 h-12 bg-slate-100 rounded"></div>
                </div>
            </div>

            {{-- Section 2 --}}
             <div>
                <div class="h-4 bg-slate-200 rounded w-32 mb-4"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="md:col-span-2 h-24 bg-slate-100 rounded"></div>
                     <div class="md:col-span-2 h-20 bg-slate-100 rounded"></div>
                </div>
            </div>
             {{-- Section 3 --}}
             <div>
                <div class="h-4 bg-slate-200 rounded w-32 mb-4"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="h-12 bg-slate-100 rounded"></div>
                     <div class="h-12 bg-slate-100 rounded"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="detail-content" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Detail Aduan</h1>
            <p class="text-slate-500 mt-1 text-sm">Informasi lengkap tiket #{{ $complaint->ticket ?? $complaint->id }}</p>
        </div>
        
        <a href="{{ route('complaint-list') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 hover:text-[#0f172a] transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="bg-[#398caf] px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-white tracking-wide">TIKET #{{ $complaint->ticket ?? $complaint->id }}</h2>
                    
                    @php
                        $status = $complaint->latestInspection?->new_status;
                        $statusBadge = \App\Models\Complaints::getStatusConfig($status);
                        $label = $statusBadge['label'] ?? 'Belum ada inspeksi';
                    @endphp
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-white/10 text-white border border-white/20">
                        {{ $label }}
                    </span>
                </div>
                <p class="text-blue-200/80 text-xs mt-1">
                    Dibuat pada {{ \Carbon\Carbon::parse($complaint->created_at)->translatedFormat('l, d F Y - H:i') }} WIB
                </p>
            </div>

            <button class="inline-flex items-center gap-2 px-4 py-2 bg-black hover:bg-gray-800 cursor-pointer text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Proses Aduan
            </button>
        </div>

        <div class="p-6 md:p-8 space-y-8">
            
            <div>
                <h3 class="text-xs font-bold text-[#2E86AB] uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Informasi Akun Terlapor</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-1.5">Username Akun</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 font-semibold text-sm">
                            {{ $complaint->username }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-1.5">Platform Media Sosial</label>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-sm flex items-center gap-2">
                            <span class="p-1 bg-white rounded-full shadow-sm">
                                <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-1.07 3.97-2.9 5.39z"/></svg>
                            </span>
                            {{ $complaint->platform->name ?? 'Tidak Diketahui' }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-1.5">Tautan Konten (URL)</label>
                        <a href="{{ $complaint->account_url }}" target="_blank" 
                           class="group flex items-center justify-between w-full px-4 py-3 bg-blue-50/50 border border-blue-100 rounded-lg text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition-all text-sm">
                            <span class="truncate pr-4 font-medium">{{ $complaint->account_url }}</span>
                            <svg class="w-4 h-4 shrink-0 opacity-50 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold text-[#2E86AB] uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Bukti & Keterangan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-1.5">Deskripsi Pelanggaran</label>
                        <div class="px-4 py-4 bg-slate-50 border border-slate-200 rounded-lg text-slate-600 text-sm leading-relaxed min-h-[100px]">
                            {{ $complaint->description ?? 'Tidak ada deskripsi tambahan.' }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-slate-500 uppercase mb-1.5">Bukti Tangkapan Layar</label>
                       @if ($complaint->bukti)
                            <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-lg bg-white">
                                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-500 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-700">Lampiran Bukti.jpg</p>
                                    <p class="text-xs text-slate-400">Klik tombol di kanan untuk melihat gambar.</p>
                                </div>

                                <a href="{{ route('admin.bukti', encrypt($complaint->id)) }}" target="_blank" 
                                   class="px-4 py-2 bg-white border border-slate-300 text-slate-600 text-xs font-bold rounded hover:bg-slate-50 transition-colors shadow-sm">
                                    LIHAT GAMBAR
                                </a>
                            </div>
                        @else
                            <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-400 text-sm italic">
                                Tidak ada lampiran bukti.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-bold text-[#2E86AB] uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Riwayat Proses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-xs text-slate-500">Tanggal Masuk</span>
                        <span class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($complaint->submitted_at)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded border border-slate-100">
                        <span class="text-xs text-slate-500">Terakhir Diupdate</span>
                        <span class="text-sm font-medium text-slate-700">
                            {{ $complaint->latestInspection?->inspected_at
                                ? \Carbon\Carbon::parse($complaint->latestInspection->inspected_at)->format('d M Y')
                                : '-' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            const skeleton = document.getElementById('detail-skeleton');
            const content = document.getElementById('detail-content');
            
            skeleton.style.display = 'none';
            content.classList.remove('hidden', 'opacity-0');
        }, 2000);
    });
</script>

@endsection
