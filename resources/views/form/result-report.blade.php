@extends('layouts.master')
@section('content')

<section class="relative w-full min-h-screen flex items-center justify-center py-24 bg-[#f8fafc] overflow-hidden">
    
    <div class="absolute inset-0 z-0 pointer-events-none opacity-10" 
         style="background-image: url('{{ asset('images/Final-Pattern.png') }}'); background-repeat: repeat; background-size: 700px auto;">
    </div>

    <div class="relative z-10 max-w-3xl w-full px-6">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            
            <div class="px-8 py-6 border-b-4 border-[#2E86AB] bg-gradient-to-r from-blue-50 to-blue-25">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-[#2E86AB] rounded-full text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-[#2E86AB] tracking-wide">Hasil Pelacakan Aduan</h2>
                        <p class="text-[#2E86AB] text-sm mt-1">Berikut adalah detail laporan Anda</p>
                    </div>
                </div>
            </div>
            
            <div class="p-8 md:p-10 space-y-8">
                
                <!-- Status Badge -->
                <div class="flex items-center gap-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900">Status Laporan</h3>
                        <p class="text-blue-700 text-sm">Laporan Anda telah diterima dan sedang diproses oleh tim kami</p>
                    </div>
                </div>

                <!-- Kode Laporan dan Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Kode Laporan</p>
                        <p class="text-xl font-bold text-[#0f172a] font-mono break-all">{{ $laporan->ticket ?? $laporan->id }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Tanggal Laporan</p>
                        <p class="text-xl font-bold text-[#0f172a]">
                            @if(is_string($laporan->submitted_at))
                                {{ \Carbon\Carbon::parse($laporan->submitted_at)->format('d M Y') }}
                            @else
                                {{ $laporan->submitted_at->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Detail Konten -->
                <div class="space-y-4 p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <h3 class="text-sm font-bold text-[#2E86AB] uppercase tracking-wider pb-2 border-b border-slate-200">Detail Konten</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Platform</p>
                            <p class="text-slate-700 font-medium">{{ $laporan->platform->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Nama Pengguna</p>
                                <p class="text-slate-700 font-medium">{{ $laporan->username ?? 'N/A' }}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Status</p>
                            @php
                                $status = $laporan->latestInspection?->new_status;
                                $statusBadge = \App\Models\Complaints::getStatusConfig($status);
                                $bg = $statusBadge['bg'] ?? 'bg-gray-100';
                                $text = $statusBadge['text'] ?? 'text-gray-800';
                                $label = $statusBadge['label'] ?? 'Belum ada inspeksi';
                            @endphp
                            <div class="flex items-center">
                                <div class="inline-flex items-center px-4 py-2 {{ $bg }} {{ $text }} rounded-full text-sm font-semibold">
                                    <span class="inline-block w-2 h-2 {{ str_replace('text-', 'bg-', $text) }} rounded-full mr-2"></span>
                                    {{ $label }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- URL Konten -->
                <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Tautan Konten</p>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        <p class="text-slate-700 font-mono text-sm break-all">{{ $laporan->account_url ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Deskripsi/Alasan Pelaporan -->
                <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Alasan Pelaporan</p>
                    <p class="text-slate-700 leading-relaxed">{{ $laporan->description ?? 'N/A' }}</p>
                </div>

                <!-- Info Tambahan -->
                <div class="p-4 bg-blue-50 border-l-4 border-[#2E86AB] rounded">
                    <p class="text-sm text-blue-900">
                        <span class="font-semibold">Informasi:</span> Laporan Anda telah dicatat dalam sistem kami. Kami akan terus memantau dan memproses laporan ini sesuai dengan prosedur yang berlaku. Anda akan menerima notifikasi jika ada perkembangan status laporan.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-200 flex items-center justify-between gap-4">
                    <button onclick="openLacakLaporanModal()" class="px-6 py-3 text-slate-600 font-medium hover:text-slate-800 transition-colors">
                        ← Cari Laporan Lain
                    </button>
                    <a href="/" class="px-8 py-3 bg-[#0f172a] text-white font-bold rounded-lg hover:bg-[#1e293b] focus:outline-none focus:ring-2 focus:ring-[#0f172a] focus:ring-offset-2 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
