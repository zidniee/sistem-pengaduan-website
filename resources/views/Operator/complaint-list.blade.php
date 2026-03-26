@extends('layouts.admin-dashboard')

@section('dashboard-content')

{{-- 1. HEADER & FILTER (LANGSUNG TAMPIL, TANPA SKELETON) --}}
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Riwayat Aduan</h1>
            <p class="text-slate-500 mt-1 text-sm">Pantau status dan tindak lanjut laporan Anda di sini.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openExportPDFModal()" 
                class="inline-flex items-center gap-2 px-5 py-2 bg-[#2E86AB] hover:bg-[#246d8a] text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Export PDF
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-4 md:items-end w-full">
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            
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

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                    Status Akun
                </label>
                <div class="relative">
                    <select name="account_status" class="w-full pl-3 pr-8 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] bg-white cursor-pointer appearance-none text-slate-600">
                        <option value="">Semua Status</option>
                        <option value="Masih Aktif" {{ $accountStatus == 'Masih Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Telah Diblokir" {{ $accountStatus == 'Telah Diblokir' ? 'selected' : '' }}>Ditolak</option>
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
                <a href="{{ route('complaint-list') }}" class="flex items-center justify-center gap-2 px-3 py-2 bg-white border border-slate-300 text-slate-500 text-sm font-medium rounded-lg hover:bg-slate-50 transition-all" title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- 2. SKELETON TABLE (Hanya muncul saat loading) --}}
<div id="table-skeleton" class="animate-pulse">
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
            <div class="h-4 bg-slate-200 rounded w-32"></div>
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
                <a href="{{ route('complaint-list') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2E86AB] text-white rounded-lg font-medium hover:bg-[#1f5a7a] transition-all shadow-md">
                    Buat Laporan Pertama
                </a>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-slate-50">
                    <table class="w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 font-semibold sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left whitespace-nowrap w-16">No</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Platform</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Tiket</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Username</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Link Aduan</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Tanggal Aduan</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Tanggal Diperbaharui</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Status Akun</th>
                                <th class="px-4 py-3 text-left whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach($complaints as $index => $complaint)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-600">{{ $complaints->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium whitespace-nowrap">
                                        {{ $complaint->platform->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded whitespace-nowrap">{{ $complaint->ticket ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3"><span class="font-medium whitespace-nowrap">{{ $complaint->username }}</span></td>
                                <td class="px-4 py-3">
                                    @if($complaint->account_url)
                                        <a href="{{ $complaint->account_url }}" target="_blank" class="inline-flex items-center gap-1 text-[#2E86AB] hover:text-[#1f5a7a] hover:underline whitespace-nowrap" title="Buka Link">
                                            <span>Buka Link</span>
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm whitespace-nowrap">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($complaint->submitted_at)->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($complaint->latestInspection?->inspected_at)->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $complaint->latestInspection?->new_status;
                                        $statusBadge = \App\Models\Complaints::getStatusConfig($status);
                                    @endphp 
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $statusBadge['bg'] ?? 'bg-gray-100' }} {{ $statusBadge['text'] ?? 'text-gray-800' }}">
                                        {{ $statusBadge['label'] ?? 'Belum ada inspeksi' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $account_status = $complaint->latestInspection?->account_status;
                                        $accountStatusBadge = \App\Models\Complaints::getAccountStatusConfig($account_status);
                                    @endphp 
                                   <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $accountStatusBadge['bg'] ?? 'bg-gray-100' }} {{ $accountStatusBadge['text'] ?? 'text-gray-800' }}">
                                        {{ $accountStatusBadge['label'] ?? 'Belum ada Inspeksi'}}
                                    </span> 
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <a href="{{ route('complaint.detail', encrypt($complaint->id)) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#2E86AB]/10 text-[#2E86AB] hover:bg-[#2E86AB] hover:text-white font-medium rounded-md transition-all text-xs">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Lihat
                                        </a>
                                        @if($complaint->bukti)
                                        <a href="{{ route('complaint.download-evidence', encrypt($complaint->id)) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white font-medium rounded-md transition-all text-xs"
                                           title="Download Bukti Screenshot">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download
                                        </a>
                                        @endif
                                        <button type="button"
                                            class="complaint-edit-btn inline-flex items-center gap-1 px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white font-medium rounded-md transition-all text-xs"
                                            data-update-url="{{ route('complaint.update', encrypt($complaint->id)) }}"
                                            data-ticket="{{ $complaint->ticket }}"
                                            data-account-url="{{ $complaint->account_url }}"
                                            data-platform-id="{{ $complaint->platform_id }}"
                                            data-submitted-at="{{ $complaint->submitted_at ? \Carbon\Carbon::parse($complaint->submitted_at)->format('Y-m-d') : '' }}"
                                            data-checked-at="{{ $complaint->latestInspection?->inspected_at ? \Carbon\Carbon::parse($complaint->latestInspection->inspected_at)->format('Y-m-d') : '' }}"
                                            data-status="{{ $complaint->latestInspection?->new_status }}"
                                            data-status="{{ $complaint->account_status }}"
                                            data-screenshot-url="{{ $complaint->screenshot_url ?? '' }}"
                                            data-description="{{ $complaint->description ?? '' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Edit
                                        </button>
                                        @include('Operator.partials.edit-complaints')
                                    </div>
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
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.complaint-edit-btn');
        if (button) {
            openEditAduanModalFromButton(button);
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditAduanModal();
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        // Simulasi Loading 1.5 detik (bisa dihapus jika data sudah cepat)
        setTimeout(() => {
            const skeleton = document.getElementById('table-skeleton');
            const content = document.getElementById('table-content');
            
            if (skeleton && content) {
                skeleton.style.display = 'none';
                content.classList.remove('hidden', 'opacity-0');
            }
        }, 1500); 
    });
    // Fungsi untuk membuka modal edit aduan dan mengisi data
    function openExportPDFModal() {
        document.getElementById('exportPDFModal').classList.remove('hidden');
        document.getElementById('exportPDFModal').classList.add('flex');
    }

    function closeExportPDFModal() {
        document.getElementById('exportPDFModal').classList.add('hidden');
        document.getElementById('exportPDFModal').classList.remove('flex');
    }

    function exportPDF() {
        const search = document.getElementById('export-search-pdf').value;
        const status = document.getElementById('export-status-pdf').value;
        const semester = document.getElementById('export-semester-pdf').value;
        const month = document.getElementById('export-month-pdf').value;
        
        let url = '{{ route("laporan.audit.pdf") }}';
        const params = new URLSearchParams();
        
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (semester) params.append('semester', semester);
        if (month) params.append('month', month);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.open(url, '_blank');
        closeExportPDFModal();
    }

    // Close modal saat click outside
    document.addEventListener('click', function(event) {
        const pdfModal = document.getElementById('exportPDFModal');
        
        if (pdfModal && event.target === pdfModal) {
            closeExportPDFModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const semesterInput = document.getElementById('export-semester-pdf');
        const monthInput = document.getElementById('export-month-pdf');

        if (semesterInput && monthInput) {
            semesterInput.addEventListener('change', function() {
                if (this.value) {
                    monthInput.value = '';
                }
            });

            monthInput.addEventListener('change', function() {
                if (this.value) {
                    semesterInput.value = '';
                }
            });
        }
    });

    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.delete('page'); // Reset to page 1 when changing per_page
        window.location.href = url.toString();
    }
</script>

@include('Operator.partials.exports.export-pdf-modal')
@endsection