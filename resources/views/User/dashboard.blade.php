@extends('layouts.user-dashboard')

@section('user-dashboard-content')
<div id="dashboard-content">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Skeleton Loading -->
    <div id="stats-skeleton" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Skeleton Card 1 -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-24 mb-3"></div>
                    <div class="h-8 bg-gray-300 rounded w-12"></div>
                </div>
                <div class="w-14 h-14 bg-gray-200 rounded-full"></div>
            </div>
        </div>

        <!-- Skeleton Card 2 -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-32 mb-3"></div>
                    <div class="h-8 bg-gray-300 rounded w-12"></div>
                </div>
                <div class="w-14 h-14 bg-gray-200 rounded-full"></div>
            </div>
        </div>

        <!-- Skeleton Card 3 -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 animate-pulse">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-20 mb-3"></div>
                    <div class="h-8 bg-gray-300 rounded w-12"></div>
                </div>
                <div class="w-14 h-14 bg-gray-200 rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- Actual Stats Cards -->
    <div id="stats-content" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Aduan -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Aduan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalComplaints ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sedang Diproses -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ $processingComplaints ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Selesai</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $completedComplaints ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('reports') }}" class="flex items-center gap-4 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#2E86AB] hover:bg-[#2E86AB]/5 transition-all group">
                <div class="w-12 h-12 bg-[#2E86AB]/10 rounded-lg flex items-center justify-center group-hover:bg-[#2E86AB]/20 transition-all">
                    <svg class="w-6 h-6 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Buat Laporan Baru</h3>
                    <p class="text-sm text-gray-500">Laporkan konten negatif</p>
                </div>
            </a>

            <a href="{{ route('user.history') }}" class="flex items-center gap-4 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-[#2E86AB] hover:bg-[#2E86AB]/5 transition-all group">
                <div class="w-12 h-12 bg-[#2E86AB]/10 rounded-lg flex items-center justify-center group-hover:bg-[#2E86AB]/20 transition-all">
                    <svg class="w-6 h-6 text-[#2E86AB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Lihat Riwayat</h3>
                    <p class="text-sm text-gray-500">Pantau status laporan Anda</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h2>
        @if($complaints->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">Belum ada aktivitas</p>
                <p class="text-sm text-gray-400 mt-1">Buat laporan pertama Anda untuk melihat aktivitas di sini</p>
            </div>
        @else
            <!-- Activity List -->
            <div class="space-y-3">
                @foreach($complaints as $complaint)
                <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $complaint->platform->name ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($complaint->description, 60) }}</p>
                            </div>
                            @php
                                $status = $complaint->latestInspection?->new_status;
                                $statusBadge = \App\Models\Complaints::getStatusConfig($status);
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusBadge['bg'] ?? 'bg-gray-100' }} {{ $statusBadge['text'] ?? 'text-gray-800' }} flex-shrink-0">
                                {{ $statusBadge['label'] ?? 'Belum diinspeksi' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-500">
                            <span class="font-mono">{{ $complaint->ticket ?? '-' }}</span>
                            <span>{{ $complaint->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

            
<script>
// Show actual content and hide skeleton after short delay
    window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.getElementById('stats-skeleton').classList.add('hidden');
            document.getElementById('stats-content').classList.remove('hidden');
        }, 500);
    });
</script>

@endsection
