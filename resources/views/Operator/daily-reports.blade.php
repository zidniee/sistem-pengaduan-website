@extends('layouts.admin-dashboard')

@section('dashboard-content')
<div id="daily-skeleton" class="animate-pulse space-y-4">
    <div class="flex justify-between items-end mb-4">
        <div class="space-y-2">
            <div class="h-8 bg-slate-300 rounded w-48"></div>
            <div class="h-4 bg-slate-200 rounded w-64"></div>
        </div>
    </div>

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

<div id="daily-content" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Laporan per Hari</h1>
        <p class="text-slate-500 mt-1 text-sm">Pantau status dan tindak lanjut laporan Anda di sini.</p>
    </div>
</div>

<!-- Laporan List -->
<div class="space-y-4">
    @if($complaints->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Laporan</h3>
            <p class="text-gray-500 mb-6">Belum ada laporan baru hari ini.</p>
        </div>
    @else
        <!-- Laporan Table -->
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
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($complaints as $index => $complaint)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600">{{ $loop->iteration }}</td>
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
                            <td class="px-4 py-3">
                                @php
                                    $status = $complaint->latestInspection?->new_status;
                                    $statusBadge = \App\Models\Complaints::getStatusConfig($status);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusBadge['bg'] ?? 'bg-gray-100' }} {{ $statusBadge['text'] ?? 'text-gray-800' }}">
                                    {{ $statusBadge['label'] ?? 'Belum ada inspeksi' }}
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
        setTimeout(() => {
            const skeleton = document.getElementById('daily-skeleton');
            const content = document.getElementById('daily-content');
            
            skeleton.style.display = 'none';
            content.classList.remove('hidden', 'opacity-0');
        }, 2000);
    });
</script>
@endsection

