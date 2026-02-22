<!-- Edit Complaint Modal Dialog -->
<div id="editAduanModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeEditAduanModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex min-h-full items-start justify-center pt-20 md:pt-24 pb-4 sm:pb-6 px-2 sm:px-4">
        <div class="relative bg-white rounded-lg sm:rounded-2xl shadow-2xl w-full max-w-3xl transform transition-all max-h-[calc(100dvh-6rem)] md:max-h-[calc(100dvh-7rem)] overflow-hidden flex flex-col">
            
            <!-- Header -->
            <div class="bg-[#2E5C99] rounded-t-lg sm:rounded-t-2xl px-4 py-3 sm:px-6 sm:py-5 flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-base sm:text-xl font-semibold text-white truncate">Edit Aduan</h2>
                    <p class="text-white/90 text-xs sm:text-sm mt-0.5 truncate">Ubah Informasi Aduan</p>
                </div>
                <button onclick="closeEditAduanModal()" class="text-white hover:bg-white/10 rounded-lg p-1.5 sm:p-2 transition-colors flex-shrink-0 ml-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editAduanForm" method="POST" action="{{ $complaint ? route('complaint.update', encrypt($complaint->id)) : '#' }}" enctype="multipart/form-data" class="flex flex-col min-h-0 flex-1">
                @csrf
                @method('PUT')
                <!-- Form Content -->
                <div class="p-4 sm:p-6 md:p-8 space-y-4 sm:space-y-5 overflow-y-auto flex-1 min-h-0">
                <!-- Tiket Aduan -->
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                        Tiket Aduan<span class="text-red-500"></span>
                    </label>
                    <input type="text" id="detail_ticket" name="ticket"
                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                           placeholder="Masukkan tiket aduan">
                </div>

                <!-- Link Konten -->
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                        Link Konten<span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="detail_link_content" name="account_url" readonly
                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <!-- Platform -->
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                        Platform<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="detail_platform" name="platform_id" disabled
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none appearance-none">
                            <option value="">Pilih Platform</option>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Aduan & Tanggal Cek -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                    <div>
                        <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                            Tanggal Aduan<span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="detail_complaint_date" readonly
                               class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                            Tanggal Cek
                        </label>
                        <input type="date" id="detail_check_date" name="checked_at"
                               class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                        Status<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="detail_status" name="new_status"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none appearance-none">
                            <option value="">Pilih Status</option>
                            <option value="sedang-diproses">Sedang Diproses</option>
                            <option value="sedang-diverifikasi">Sedang Diverifikasi</option>
                            <option value="laporan-diterima">Laporan Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Status Akun -->
                 <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">
                        Status Akun<span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="detail_status" name="account_status"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none appearance-none">
                            <option value="">Pilih Status</option>
                            <option value="Masih Aktif">Masih Aktif</option>
                            <option value="Telah Diblokir">Telah Diblokir</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Tangkapan Layar -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-333 mb-1.5 sm:mb-2">
                        Tangkapan Layar (Screenshots) <span class="text-red-500">*</span>
                    </label>
                     @if ($complaint && $complaint->bukti)
                            <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-lg bg-white">
                                <a href="{{ route('admin.bukti', encrypt($complaint->id)) }}" target="_blank" 
                                   class="px-4 py-2 bg-white border border-slate-300 text-slate-600 text-xs font-bold rounded hover:bg-slate-50 transition-colors shadow-sm">
                                    <img class="size-50" src="{{ route('admin.bukti', encrypt($complaint->id)) }}" />
                                </a>
                            </div>
                        @else
                            <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-400 text-sm italic">
                                Tidak ada lampiran bukti.
                            </div>
                        @endif
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-gray-700 text-xs sm:text-sm font-medium mb-1.5 sm:mb-2">Deskripsi</label>
                    <textarea id="detail_description" name="description" rows="4"
                              class="w-full px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-700 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-2">
                    <button type="button" onclick="closeEditAduanModal()"
                            class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 w-full sm:w-auto">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 sm:px-5 py-2 text-xs sm:text-sm font-semibold text-white bg-[#2E5C99] rounded-lg hover:bg-[#244a7a] w-full sm:w-auto">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditAduanModalFromButton(button) {
        const modal = document.getElementById('editAduanModal');
        const form = document.getElementById('editAduanForm');
        if (!modal || !form) return;

        form.action = button.dataset.updateUrl || '';
        document.getElementById('detail_ticket').value = button.dataset.ticket || '';
        document.getElementById('detail_link_content').value = button.dataset.accountUrl || '';
        document.getElementById('detail_platform').value = button.dataset.platformId || '';
        document.getElementById('detail_complaint_date').value = button.dataset.submittedAt || '';
        document.getElementById('detail_check_date').value = button.dataset.checkedAt || '';
        document.getElementById('detail_status').value = button.dataset.status || '';
        resetScreenshotsContainer();

        const desc = document.getElementById('detail_description');
        if (desc) desc.value = button.dataset.description || '';

        modal.classList.remove('hidden');
    }

    function closeEditAduanModal() {
        const modal = document.getElementById('editAduanModal');
        if (modal) modal.classList.add('hidden');
    }

    function resetScreenshotsContainer() {
        const container = document.getElementById('screenshots-container');
        if (!container) return;

        container.innerHTML = `
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 p-2 bg-white rounded-lg border border-slate-200 shadow-sm">
                <div class="hidden sm:block p-2 bg-blue-50 rounded text-[#2E86AB] flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <input type="file" name="screenshots[]" required accept="image/jpeg,image/png,image/jpg" data-validate-file="image" class="js-validate-file flex-1 text-xs sm:text-sm text-slate-500 file:mr-2 sm:file:mr-4 file:py-1 file:px-2 sm:file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#2E86AB] file:text-white hover:file:bg-[#246b8a] cursor-pointer">
            </div>
        `;
    }

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

    document.addEventListener('click', function(e) {
        const addBtn = e.target.closest('#add-screenshot-btn');
        if (!addBtn) return;

        const container = document.getElementById('screenshots-container');
        if (!container) return;

        const currentCount = container.querySelectorAll('input[type="file"]').length;
        if (currentCount >= 5) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 p-2 bg-white rounded-lg border border-slate-200 shadow-sm';
        wrapper.innerHTML = `
            <div class="hidden sm:block p-2 bg-blue-50 rounded text-[#2E86AB] flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <input type="file" name="screenshots[]" required accept="image/jpeg,image/png,image/jpg" data-validate-file="image" class="js-validate-file flex-1 text-xs sm:text-sm text-slate-500 file:mr-2 sm:file:mr-4 file:py-1 file:px-2 sm:file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#2E86AB] file:text-white hover:file:bg-[#246b8a] cursor-pointer">
        `;

        container.appendChild(wrapper);
    });
</script>