@extends('layouts.admin-dashboard')

@section('dashboard-content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Import Data Laporan</h1>
            <p class="text-slate-500 mt-1 text-sm">Unggah file Excel untuk memasukkan data laporan secara massal.</p>
        </div>
        <a href="{{ route('laporan.import.history') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Lihat History
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-[#0f172a]">Form Import Laporan</h2>
                <p class="text-sm text-slate-500 mt-1">Pilih file Excel (.xlsx) yang berisi data laporan untuk diunggah.</p>
            </div>
            
            <div class="p-6 space-y-6">
                @if (isset($errors) && $errors->any())
                    <div class="p-4 rounded-lg bg-red-50 border border-red-200 flex gap-3">
                        <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-bold text-red-800">Gagal Mengunggah</h3>
                            <ul class="list-disc pl-4 mt-1 text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if (session('import_summary'))
                    @php $summary = session('import_summary'); @endphp
                    <div class="rounded-lg border {{ count($summary['failed_rows']) > 0 ? 'bg-amber-50 border-amber-200' : 'bg-emerald-50 border-emerald-200' }} p-4">
                        <div class="flex items-start gap-3">
                            <div class="shrink-0">
                                @if(count($summary['failed_rows']) > 0)
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="w-full">
                                <h3 class="text-sm font-bold {{ count($summary['failed_rows']) > 0 ? 'text-amber-800' : 'text-emerald-800' }}">
                                    Proses Import Selesai
                                </h3>
                                <div class="mt-3 grid grid-cols-3 gap-3 text-center">
                                    <div class="bg-white/60 rounded p-3">
                                        <span class="block text-xs text-slate-500 font-medium">Total Baris</span>
                                        <span class="block font-bold text-lg text-slate-700 mt-1">{{ $summary['total_rows'] }}</span>
                                    </div>
                                    <div class="bg-white/60 rounded p-3">
                                        <span class="block text-xs text-emerald-600 font-medium">Berhasil</span>
                                        <span class="block font-bold text-lg text-emerald-700 mt-1">{{ $summary['success_count'] }}</span>
                                    </div>
                                    <div class="bg-white/60 rounded p-3">
                                        <span class="block text-xs text-red-600 font-medium">Gagal</span>
                                        <span class="block font-bold text-lg text-red-700 mt-1">{{ count($summary['failed_rows']) }}</span>
                                    </div>
                                </div>

                                @if (count($summary['failed_rows']) > 0)
                                    <div class="mt-4 pt-4 border-t border-amber-200">
                                        <p class="text-xs font-semibold text-amber-800 mb-2">Detail Error:</p>
                                        <div class="max-h-40 overflow-y-auto bg-white/70 rounded border border-amber-200 p-3 text-xs text-amber-800 space-y-1">
                                            @foreach ($summary['failed_rows'] as $failure)
                                                <div class="flex gap-2">
                                                    <span class="font-semibold shrink-0">Baris {{ $failure['row'] }}:</span>
                                                    <span>{{ $failure['reason'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('laporan.import') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">File Excel (.xlsx)</label>
                        <div class="relative group">
                            <input type="file" name="file" id="file" accept=".xlsx" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                onchange="updateFileName(this)">
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center transition-all group-hover:border-[#2E86AB] group-hover:bg-blue-50/30">
                                <div class="w-12 h-12 bg-blue-50 text-[#2E86AB] rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-600 font-medium" id="file-label">
                                    Klik untuk upload atau seret file ke sini
                                </p>
                                <p class="text-xs text-slate-400 mt-1">Maksimal 5MB, format .xlsx</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2E86AB] hover:bg-[#246d8a] text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Proses Import
                        </button>
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-50 transition-all">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-gradient-to-br from-[#2E86AB]/5 to-[#F59E0B]/5 border border-[#2E86AB]/20 rounded-xl p-5 sticky top-20">
            <h3 class="text-[#0f172a] font-bold text-sm mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Format File Excel
            </h3>
            
            <div class="space-y-3 text-xs text-slate-600">
                <p class="text-slate-700 font-medium">Kolom yang wajib ada (Baris 1):</p>
                
                <ul class="space-y-2 pl-1">
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>nama_akungrup</b> - Nama akun/grup yang dilaporkan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>link</b> - URL akun/postingan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>tanggal</b> - Format Y-m-d atau Excel date</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>tiket</b> - Nomor referensi</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>tanggal_tracking</b> - Format Y-m-d</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>status</b> - Sedang Diproses / Sedang Diverifikasi / Laporan Diterima / Ditolak</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>account_status</b> - Masih Aktif atau Telah Diblokir</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 bg-[#2E86AB] rounded-full mt-1.5 shrink-0"></span>
                        <span><b>bukti</b> - Link Google Drive atau nama file lokal</span>
                    </li>
                </ul>

                <div class="pt-4 border-t border-[#2E86AB]/10 mt-4">
                    <a href="{{ route('laporan.template.download') }}" class="text-[#2E86AB] font-medium hover:underline flex items-center gap-1">
                        Download Template Excel
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>

                <div class="pt-3 mt-3 border-t border-[#2E86AB]/10">
                    <p class="text-slate-500 text-[11px] mb-2">Platform yang didukung:</p>
                    <div class="flex flex-wrap gap-1">
                        <span class="bg-[#2E86AB]/10 text-[#2E86AB] px-2 py-1 rounded text-[11px] font-medium">X (Twitter)</span>
                        <span class="bg-[#2E86AB]/10 text-[#2E86AB] px-2 py-1 rounded text-[11px] font-medium">Instagram</span>
                        <span class="bg-[#2E86AB]/10 text-[#2E86AB] px-2 py-1 rounded text-[11px] font-medium">Facebook</span>
                        <span class="bg-[#2E86AB]/10 text-[#2E86AB] px-2 py-1 rounded text-[11px] font-medium">TikTok</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function updateFileName(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.classList.add('text-[#2E86AB]', 'font-semibold');
        } else {
            label.textContent = "Klik untuk upload atau seret file ke sini";
            label.classList.remove('text-[#2E86AB]', 'font-semibold');
        }
    }
</script>

@endsection