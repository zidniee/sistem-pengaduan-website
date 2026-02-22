<!-- Track Complaint Modal Dialog -->
<div id="lacakLaporanModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeLacakLaporanModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all">
            
            <!-- Header -->
            <div class="px-8 py-6 bg-gradient-to-r from-[#2E86AB] to-[#0f172a] rounded-t-2xl relative overflow-hidden">
                <!-- Pattern Background -->
                <div class="absolute inset-0 opacity-10 pointer-events-none" 
                     style="background-image: url('{{ asset('images/Final-Pattern.png') }}'); background-repeat: repeat; background-size: 300px auto;">
                </div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-wide flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Lacak Aduan
                        </h2>
                        <p class="text-white/80 text-sm mt-1">Masukkan kode laporan untuk melacak status aduan Anda.</p>
                    </div>
                    <button onclick="closeLacakLaporanModal()" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Body -->
            <div class="p-8 md:p-10">
                @if (isset($errors) && $errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Terjadi kesalahan:
                        </p>
                        <ul class="list-disc list-inside text-red-700 text-sm mt-2 ml-7">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('track_error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-800 font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('track_error') }}
                        </p>
                    </div>
                @endif

                <form action="{{ route('track-laporan') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-3">
                        <label for="kode_laporan" class="block text-sm font-medium text-slate-700 mb-2">
                            Kode Laporan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#2E86AB]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                id="kode_laporan" 
                                name="kode_laporan" 
                                required 
                                placeholder="Contoh: TKT-123ABC"
                                value="{{ old('kode_laporan') }}"
                                maxlength="100"
                                class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2E86AB] focus:border-transparent bg-slate-50 transition-all text-slate-700">
                        </div>
                        <p class="text-xs text-slate-500 ml-1">Masukkan kode laporan yang Anda terima saat mengajukan aduan</p>
                    </div>

                    <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                        <button type="button"
                            onclick="closeLacakLaporanModal()"
                            class="px-6 py-2.5 text-slate-600 font-medium hover:text-slate-800 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-2.5 bg-gradient-to-r from-[#2E86AB] to-[#0f172a] text-white font-bold rounded-lg hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[#2E86AB] focus:ring-offset-2 transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal JavaScript -->
<script>
    function openLacakLaporanModal() {
        document.getElementById('lacakLaporanModal').classList.remove('hidden');
        // Focus on input when modal opens
        setTimeout(() => {
            document.getElementById('kode_laporan')?.focus();
        }, 100);
    }

    function closeLacakLaporanModal() {
        document.getElementById('lacakLaporanModal').classList.add('hidden');
        // Clear input and errors when closing
        const input = document.getElementById('kode_laporan');
        if (input) input.value = '';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('lacakLaporanModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeLacakLaporanModal();
            }
        }
    });

    // Auto-open modal ONLY if there are errors in kode_laporan field
    @if ((isset($errors) && $errors->has('kode_laporan')) || session('track_error'))
        document.addEventListener('DOMContentLoaded', function() {
            openLacakLaporanModal();
        });
    @endif
</script>
