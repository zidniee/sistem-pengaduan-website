<!-- Modal Export PDF -->
<div id="exportPDFModal" class="hidden fixed inset-0 z-50 flex items-start justify-center pt-20 md:pt-24 pb-6 bg-black/50 transition-all duration-300 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6 border border-slate-200">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-[#0f172a]">Export Laporan PDF</h3>
                <p class="text-sm text-slate-500 mt-1">Tentukan filter untuk laporan Anda</p>
            </div>
            <button onclick="closeExportPDFModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form Filter -->
        <div class="space-y-4 mb-6">
            <!-- Search -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Cari (Tiket, Platform, Username)
                </label>
                <div class="relative">
                    <input type="text" id="export-search-pdf" placeholder="Masukkan kata kunci..." 
                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] transition-all placeholder:text-slate-400">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                <div class="relative">
                    <select id="export-status-pdf" class="w-full pl-3 pr-8 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] bg-white cursor-pointer appearance-none text-slate-600">
                        <option value="">Semua Status</option>
                        <option value="sedang-diproses">Sedang Diproses</option>
                        <option value="sedang-diverifikasi">Sedang Diverifikasi</option>
                        <option value="laporan-diterima">Laporan Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Semester -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Periode Semester</label>
                <div class="relative">
                    <select id="export-semester-pdf" class="w-full pl-3 pr-8 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] bg-white cursor-pointer appearance-none text-slate-600">
                        <option value="">Semua Periode</option>
                        @for($year = $endYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}-1">Semester 1 {{ $year }} (Jan-Jun)</option>
                            <option value="{{ $year }}-2">Semester 2 {{ $year }} (Jul-Des)</option>
                        @endfor
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Bulan -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Periode Bulan</label>
                <input type="month" id="export-month-pdf"
                    class="w-full pl-3 pr-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#2E86AB]/20 focus:border-[#2E86AB] transition-all text-slate-600">
                <p class="text-xs text-slate-500 mt-1">Jika bulan dipilih, filter semester diabaikan.</p>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-slate-200 mb-6"></div>

        <!-- Buttons -->
        <div class="flex gap-3">
            <button onclick="closeExportPDFModal()" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-all">
                Batal
            </button>
            <button onclick="exportPDF()" class="flex-1 px-4 py-2 bg-[#2E86AB] hover:bg-[#246d8a] text-white font-medium rounded-lg transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download PDF
            </button>
        </div>

        <!-- Info -->
        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-blue-700">
                <strong>Tips:</strong> Kosongkan filter untuk mengexport semua data. Maksimal 500 data per PDF.
            </p>
        </div>
    </div>
</div>