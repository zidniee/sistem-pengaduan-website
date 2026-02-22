<h3 class="text-sm font-bold text-[#2E86AB] uppercase tracking-wider border-b border-slate-200 pb-2">Detail Konten</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="col-span-1 md:col-span-2">
        <label for="platform" class="cursor-pointer block text-sm font-medium text-333 mb-2">
            Platform Media Sosial <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select id="platform" name="platform" required class="cursor-pointer w-full pl-4 pr-10 py-3 border @error('platform') border-red-500 @else border-slate-300 @enderror rounded-lg focus:outline-none @error('platform') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 appearance-none transition-all">
                <option value="" disabled>-- Pilih Platform --</option>
                @foreach($platforms as $platform)
                    <option value="{{ $platform->id }}" @selected(old('platform') == $platform->id)>{{ $platform->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
        @error('platform')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    <div>
        <label for="nama" class="block text-sm font-medium text-333 mb-2">
            Nama Akun / Pemilik <span class="text-red-500">*</span>
        </label>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: @akunpalsu" class="cursor-pointer w-full px-4 py-3 border @error('nama') border-red-500 @else border-slate-300 @enderror rounded-lg focus:outline-none @error('nama') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 transition-all">
        @error('nama')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    <div>
        <label for="tanggal" class="block text-sm font-medium text-333 mb-2">
            Tanggal Temuan <span class="text-red-500">*</span>
        </label>
        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required class="cursor-pointer w-full px-4 py-3 border @error('tanggal') border-red-500 @else border-slate-300 @enderror rounded-lg focus:outline-none @error('tanggal') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 transition-all text-slate-600">
        @error('tanggal')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
    <div class="col-span-1 md:col-span-2">
        <label for="url" class="block text-sm font-medium text-333 mb-2">
            Tautan (URL) Konten <span class="text-red-500">*</span>
        </label>
        <div class="flex">
            <span class="inline-flex items-center px-2 sm:px-3 md:px-4 rounded-l-lg border border-r-0 @error('url') border-red-500 @else border-slate-300 @enderror bg-slate-100 text-slate-500 text-xs sm:text-sm">
                https://
            </span>
            <input type="text" id="url" name="url" value="{{ old('url') }}" required placeholder="facebook.com/post/123..." class="cursor-pointer flex-1 px-2 sm:px-3 md:px-4 py-3 border @error('url') border-red-500 @else border-slate-300 @enderror rounded-r-lg focus:outline-none @error('url') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 transition-all text-sm">
        </div>
        @error('url')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                {{ $message }}
            </p>
        @enderror
    </div>
</div>