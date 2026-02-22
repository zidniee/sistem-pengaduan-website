@extends('layouts.user-dashboard')

@section('user-dashboard-content')

{{-- 1. HEADER & FILTER (LANGSUNG TAMPIL) --}}
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Riwayat Aduan</h1>
            <p class="text-slate-500 mt-1 text-sm">Pantau status dan tindak lanjut laporan Anda di sini.</p>
        </div>
        
        <a href="{{ route('reports') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0f172a] text-white text-sm font-medium rounded-lg hover:bg-[#1e293b] transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-4 md:items-end w-full">
            
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Cari Laporan
                </label>
                <div class="relative group">
                    <input name="search" type="text" value="{{ $search }}" 
                        placeholder="Cari Tiket, Platform, atau Username..." 
                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] transition-all placeholder:text-slate-400">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400 group-focus-within:text-[#2E86AB] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Status
                </label>
                <div class="relative">
                    <select name="status" class="w-full pl-3 pr-8 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] bg-white cursor-pointer appearance-none text-slate-600">
                        <option value="">Semua Status</option>
                        <option value="sedang-diproses" {{ $status == 'sedang-diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="sedang-diverifikasi" {{ $status == 'sedang-diverifikasi' ? 'selected' : '' }}>Sedang Diverifikasi</option>
                        <option value="laporan-diterima" {{ $status == 'laporan-diterima' ? 'selected' : '' }}>Laporan Diterima</option>
                        <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex items-center justify-center gap-2 px-5 py-2 bg-[#2E86AB] hover:bg-[#246d8a] text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                    Terapkan
                </button>
                
                <a href="{{ route('user.history') }}" class="flex items-center justify-center gap-2 px-3 py-2 bg-white border border-slate-300 text-slate-500 text-sm font-medium rounded-lg hover:bg-slate-50 transition-all" title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </a>
            </div>

        </form>
    </div>
</div>

{{-- 2. SKELETON TABLE (Hanya muncul saat loading) --}}
<div id="table-skeleton" class="animate-pulse space-y-4">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="h-12 bg-slate-100 border-b border-slate-200"></div>
        @for ($i = 0; $i < 5; $i++)
        <div class="flex items-center p-4 border-b border-slate-100 gap-4">
            <div class="h-4 bg-slate-200 rounded w-8"></div>
            <div class="h-4 bg-slate-200 rounded w-24"></div>
            <div class="h-4 bg-slate-200 rounded w-20"></div>
            <div class="h-4 bg-slate-200 rounded w-32"></div>
            <div class="h-4 bg-slate-200 rounded w-32"></div>
            <div class="h-4 bg-slate-200 rounded w-24"></div>
            <div class="h-4 bg-slate-200 rounded w-20"></div>
        </div>
        @endfor
    </div>
</div>

{{-- 3. REAL CONTENT (TABLE) --}}
<div id="table-content" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
    <div class="space-y-4">
        @if($complaints->isEmpty())
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-12 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Laporan</h3>
                <p class="text-gray-500 mb-6">Anda belum pernah membuat laporan aduan konten negatif</p>
                <a href="{{ route('reports') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2E86AB] text-white rounded-lg font-medium hover:bg-[#1f5a7a] transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Laporan Pertama
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left w-16">No</th>
                                <th class="px-4 py-3 text-left">Platform</th>
                                <th class="px-4 py-3 text-left">Tiket</th>
                                <th class="px-4 py-3 text-left">Username</th>
                                <th class="px-4 py-3 text-left">Link Aduan</th>
                                <th class="px-4 py-3 text-left">Tanggal Aduan</th>
                                <th class="px-4 py-3 text-left">Tanggal Diperbaharui</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach($complaints as $index => $complaint)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-600">{{ $complaints->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $complaint->platform->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $complaint->ticket ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-medium">{{ $complaint->username }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($complaint->account_url)
                                        <a href="{{ $complaint->account_url }}" target="_blank" class="inline-flex items-center gap-1 text-[#2E86AB] hover:text-[#1f5a7a] hover:underline">
                                            <span class="truncate max-w-[200px]">{{ Str::limit($complaint->account_url, 30) }}</span>
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ \Carbon\Carbon::parse($complaint->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ \Carbon\Carbon::parse($complaint->latestInspection?->inspected_at)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $complaint->latestInspection?->new_status;
                                        $statusBadge = \App\Models\Complaints::getStatusConfig($status);
                                        $bg = $statusBadge['bg'] ?? 'bg-gray-100';
                                        $text = $statusBadge['text'] ?? 'text-gray-800';
                                        $label = $statusBadge['label'] ?? 'Belum ada inspeksi';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $bg }} {{ $text }}">
                                        {{ $label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($complaints->hasPages())
                <div class="mt-6">
                    {{ $complaints->links('components.pagination') }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Simulasi Loading 1.5 detik
        setTimeout(() => {
            const skeleton = document.getElementById('table-skeleton');
            const content = document.getElementById('table-content');
            
            if (skeleton && content) {
                skeleton.style.display = 'none';
                content.classList.remove('hidden', 'opacity-0');
            }
        }, 1500); 
    });
</script>

@endsection