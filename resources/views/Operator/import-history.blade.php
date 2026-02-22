@extends('layouts.admin-dashboard')

@section('dashboard-content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">History Import Laporan</h1>
            <p class="text-slate-500 mt-1 text-sm">Pantau status dan hasil import data laporan Anda</p>
        </div>
        <a href="{{ route('laporan.import.form') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#2E86AB] hover:bg-[#1e5f7a] text-white rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Import Baru
        </a>
    </div>
</div>

@if (session('import_success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 flex gap-3">
        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="text-sm font-bold text-emerald-800">Berhasil</h3>
            <p class="text-sm text-emerald-700 mt-1">{{ session('import_success') }}</p>
        </div>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
        <h2 class="text-lg font-semibold text-[#0f172a]">History Import</h2>
        <p class="text-sm text-slate-500 mt-1">Daftar semua proses import yang pernah Anda lakukan</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">File</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Berhasil</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Gagal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($imports as $import)
                    <tr class="hover:bg-slate-50 transition-colors" id="import-row-{{ $import->id }}" data-import-id="{{ $import->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $import->filename }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($import->status == 'completed') bg-emerald-100 text-emerald-800
                                @elseif($import->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($import->status == 'failed') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                @if($import->status == 'completed') Selesai
                                @elseif($import->status == 'processing') Diproses
                                @elseif($import->status == 'failed') Gagal
                                @else Menunggu
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-32">
                                @if($import->status == 'processing' || $import->status == 'pending')
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full progress-bar" style="width: 0%"></div>
                                    </div>
                                @elseif($import->status == 'completed')
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-emerald-600 h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-500">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 total-rows">
                            {{ $import->total_rows ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-700 font-medium success-count">
                            {{ $import->success_count ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-700 font-medium failed-count">
                            {{ $import->failed_count ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <div>{{ $import->created_at->format('d M Y') }}</div>
                            <div class="text-xs">{{ $import->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($import->status == 'completed')
                                @if($import->failed_count && $import->failed_count > 0)
                                    <button type="button" onclick="showFailedRows({{ $import->id }})" class="text-[#2E86AB] hover:text-[#1e5f7a] hover:underline font-medium transition-colors">
                                        Lihat Error ({{ $import->failed_count }})
                                    </button>
                                @else
                                    <span class="text-emerald-600 font-medium">✓ Sukses</span>
                                @endif
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-medium">Belum ada history import</p>
                                <p class="text-xs mt-1">Mulai import data laporan untuk melihat history</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($imports->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $imports->links() }}
        </div>
    @endif
</div>

<div id="failedRowsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[75vh] flex flex-col overflow-hidden border border-slate-100">
        
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 leading-none">Detail Error Import</h3>
                    <p class="text-sm text-slate-500 mt-1">Beberapa baris data gagal diproses</p>
                </div>
            </div>
            <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto custom-scrollbar flex-1" id="failedRowsContent">
            <div class="space-y-4">
                <div class="p-4 bg-red-50 border border-red-100 rounded-lg">
                    <p class="text-sm font-medium text-red-800">Baris 12: Format email tidak valid</p>
                    <p class="text-xs text-red-600 mt-1">Data: user@invalid-format</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
            <button onclick="closeModal()" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 shadow-sm transition-all active:scale-95">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto-refresh for processing imports
    let refreshInterval;

    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            const processingRows = document.querySelectorAll('[data-import-id]');
            
            processingRows.forEach(row => {
                const importId = row.dataset.importId;
                const statusBadge = row.querySelector('.status-badge');
                
                if (statusBadge && (statusBadge.textContent.trim() === 'Diproses' || statusBadge.textContent.trim() === 'Menunggu')) {
                    fetch(`/operator/import-status/${importId}`)
                        .then(response => response.json())
                        .then(data => {
                            updateRow(row, data);
                            
                            // Stop refreshing if completed or failed
                            if (data.status === 'completed' || data.status === 'failed') {
                                setTimeout(() => location.reload(), 2000);
                            }
                        })
                        .catch(error => console.error('Error fetching status:', error));
                }
            });
        }, 3000); // Refresh every 3 seconds
    }

    function updateRow(row, data) {
        const statusBadge = row.querySelector('.status-badge');
        const progressBar = row.querySelector('.progress-bar');
        const totalRows = row.querySelector('.total-rows');
        const successCount = row.querySelector('.success-count');
        const failedCount = row.querySelector('.failed-count');
        
        // Update status badge
        if (data.status === 'completed') {
            statusBadge.className = 'status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800';
            statusBadge.textContent = 'Selesai';
        } else if (data.status === 'processing') {
            statusBadge.className = 'status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800';
            statusBadge.textContent = 'Diproses';
        } else if (data.status === 'failed') {
            statusBadge.className = 'status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800';
            statusBadge.textContent = 'Gagal';
        }
        
        // Update progress bar
        if (progressBar && data.progress_percentage) {
            progressBar.style.width = data.progress_percentage + '%';
        }
        
        // Update counts
        if (totalRows) totalRows.textContent = data.total_rows || '-';
        if (successCount) successCount.textContent = data.success_count || '-';
        if (failedCount) failedCount.textContent = data.failed_count || '-';
    }

    function showFailedRows(importId) {
        console.log('Fetching failed rows for import ID:', importId);
        
        fetch(`/operator/import-status/${importId}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                
                const modal = document.getElementById('failedRowsModal');
                const content = document.getElementById('failedRowsContent');
                
                if (!modal || !content) {
                    console.error('Modal elements not found');
                    alert('Error: Modal tidak ditemukan');
                    return;
                }
                
                let html = '<div class="space-y-3">';
                
                if (data.failed_rows && Array.isArray(data.failed_rows) && data.failed_rows.length > 0) {
                    console.log('Processing', data.failed_rows.length, 'failed rows');
                    data.failed_rows.forEach((failure, index) => {
                        // Escape HTML to prevent XSS
                        const row = failure.row || 'N/A';
                        const reason = (failure.reason || 'Unknown error')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#039;');
                        
                        html += `
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex gap-2">
                                    <span class="font-semibold text-red-800 shrink-0">Baris ${row}:</span>
                                    <span class="text-red-700">${reason}</span>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    console.log('No failed rows found or invalid data format');
                    html += '<p class="text-center text-slate-500">Tidak ada error yang tercatat</p>';
                }
                
                html += '</div>';
                content.innerHTML = html;
                modal.classList.remove('hidden');
                modal.style.display = 'flex'; // Ensure modal is visible
            })
            .catch(error => {
                console.error('Error fetching failed rows:', error);
                alert('Gagal memuat detail error: ' + error.message);
            });
    }

    function closeModal() {
        const modal = document.getElementById('failedRowsModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('failedRowsModal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }
    });

    // Start auto-refresh on page load
    document.addEventListener('DOMContentLoaded', () => {
        startAutoRefresh();
    });

    // Clean up interval on page unload
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
</script>
@endpush
