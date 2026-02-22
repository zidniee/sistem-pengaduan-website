<h3 class="text-sm font-bold text-[#2E86AB] uppercase tracking-wider border-b border-slate-200 pb-2">Bukti Pendukung</h3>

<div>
    <label for="alasan" class="block text-sm font-medium text-333 mb-2">
        Alasan Pelaporan <span class="text-red-500">*</span>
    </label>
    <textarea id="alasan" name="alasan" rows="4" required placeholder="Jelaskan mengapa konten ini melanggar aturan..." class="w-full px-4 py-3 border @error('alasan') border-red-500 @else border-slate-300 @enderror rounded-lg focus:outline-none @error('alasan') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 resize-none transition-all">{{ old('alasan') }}</textarea>
    @error('alasan')
        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
            {{ $message }}
        </p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-333 mb-2">
        Unggah Tangkapan Layar (Screenshots) <span class="text-red-500">*</span>
    </label>

    <div class="border-2 border-dashed @error('bukti') border-red-500 bg-red-50/30 @else border-slate-300 bg-slate-50/50 @enderror rounded-xl p-6 hover:bg-slate-50 transition-colors">
        <div id="screenshots-container" class="space-y-3 mb-4">
            <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-slate-200 shadow-sm">
                <div class="p-2 bg-blue-50 rounded text-[#2E86AB]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <input type="file" name="bukti" required accept="image/jpeg,image/png,image/jpg" data-validate-file="image" class="js-validate-file flex-1 text-sm text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#2E86AB] file:text-white hover:file:bg-[#246b8a] cursor-pointer truncate max-w-[200px] sm:max-w-none">
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-4">
            <p class="text-xs text-slate-400 italic">
                *Format: JPG/PNG. Maks: 5MB/file. (Max 5 file)
            </p>
        </div>
    </div>
    @error('bukti')
        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
            {{ $message }}
        </p>
    @enderror
</div>