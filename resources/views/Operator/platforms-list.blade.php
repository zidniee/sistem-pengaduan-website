@extends('layouts.admin-dashboard')

@section('dashboard-content')

<!-- Pickr -->
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/>

<div id="platforms-skeleton" class="animate-pulse space-y-4">
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
                <div class="h-4 bg-slate-200 rounded w-50"></div>
            </div>
            @endfor
    </div>
</div>

<div id="platforms-content" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Tambah Platform</h1>
            <p class="text-slate-500 mt-1 text-sm">Tambahkan platform sumber konten</p>
        </div>
        <div class="flex gap-2">
            <button onclick="addPlatformModal()" 
                class="inline-flex items-center gap-2 px-5 py-2 bg-[#2E86AB] hover:bg-[#246d8a] text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Tambah
            </button>
        </div>
    </div>

    <div class="space-y-4">
    @if($platforms->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Platform belum di tambahkan</h3>
            <p class="text-gray-500 mb-6">Tambahkan platform dengan menekan tombol tambah.</p>
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
                            <th class="px-4 py-3 text-left">URL</th>
                            <th class="px-4 py-3 text-left">Warna</th>
                            <th class="px-4 py-3 text-left w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($platforms as $index => $platform)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium">{{ $platform->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $platform->url }}
                            </td>
                            <td>
                                <div class="px-4 py-2 flex items-center gap-3">
                                    <span  class="w-6 h-6 rounded border border-gray-200" style="background: {{ $platform->warna }}"></span>
                                    <span  class="font-mono text-sm"> {{ $platform->warna }}</span>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('platforms.delete', $platform->id) }}" method="POST" enctype="multipart/form-data" >
                                    @csrf @method('DELETE')
                                    <button type="submit" class="complaint-edit-btn inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-medium rounded-md transition-all text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                <table>
            </div>
        </div>
    @endif
    </div>
</div>

<!-- Form Modal -->
<div class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" id="addPlatformModalContainer">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" id="addPlatformModalBackdrop"></div>
    <div class="relative bg-white shadow-2xl z-10 w-full md:max-w-[60%] max-h-[90vh] md:max-h-[85%] flex flex-col rounded-xl overflow-auto">
        <form action="{{ route('platforms.add') }}" method="POST" enctype="multipart/form-data" class="flex flex-row h-full">
            @csrf
            <div class="flex flex-col p-6 items-fill gap-4 w-[100%]">
                    <div>
                        <label for="nama" class="block text-sm font-bold text-333 mb-2">
                            Platform
                            <span class="text-red-500">*</span>
                        </label>
                         <input type="text" id="nama_platform" name="nama_platform" value="{{ old('nama_platform') }}" required placeholder="Contoh: TikTok" class="cursor-pointer w-full px-4 py-3 border @error('nama') border-red-500 @else border-slate-300 @enderror rounded-lg focus:outline-none @error('nama') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 transition-all">
                        @error('nama')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label for="url" class="block text-sm font-bold text-333 mb-2">
                            URL
                            <span class="text-red-500">*</span>
                        </label>
                         <input type="text" id="url_platform" name="url_platform" value="{{ old('url_platform') }}" required placeholder="Contoh: https://www.tiktok.com" class="cursor-pointer w-full px-4 py-3 border @error('nama') border-red-500 @else border-slate-300 @enderror rounded-lg focus:outline-none @error('nama') focus:ring-2 focus:ring-red-500 focus:border-red-500 @else focus:ring-2 focus:ring-[#2E86AB] focus:border-[#2E86AB] @enderror bg-slate-50 transition-all">
                        @error('url')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label for="string" class="block text-sm font-bold text-333 mb-2">
                            Warna
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="hidden" id="color_picker" name="warna_platform" value="#000000">
                            <button type="button" id="pickr-trigger" class="px-4 py-2 border border-gray-300 rounded-lg bg-white flex items-center gap-3 cursor-pointer">
                                <span id="colorBox" class="w-6 h-6 rounded border border-gray-200" style="background: #000000"></span>
                                <span id="colorValue" class="font-mono text-sm">#000000</span>
                            </button>
                        </div>
                        @error('string')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.353 5.647A9 9 0 000 9a9 9 0 0018.353-3.353z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
            </div>
            <div class="p-4 md:p-6 border-t border-slate-200 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 bg-slate-50">
                <button type="button" 
                        onclick="document.getElementById('addPlatformModalContainer').classList.add('hidden')" 
                        class="w-full sm:w-auto px-6 py-3 text-slate-600 font-medium hover:text-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-[#0f172a] text-white font-bold rounded-lg hover:bg-[#1e293b] transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Tambahkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            const skeleton = document.getElementById('platforms-skeleton');
            const content = document.getElementById('platforms-content');
            
            skeleton.style.display = 'none';
            content.classList.remove('hidden', 'opacity-0');
        }, 1000);
    });

    const addPlatformModalContainer = document.getElementById("addPlatformModalContainer");

    function addPlatformModal() { addPlatformModalContainer.classList.remove('hidden'); };

    addPlatformModalContainer.addEventListener('click', (e) => {
        if (e.target === modal || e.target === addPlatformModalBackdrop) {
            addPlatformModalContainer.classList.add('hidden');
        }
    });

    const pickr = Pickr.create({
        el: '#pickr-trigger',
        theme: 'classic',
        default: '#42445a',
        swatches: ['#FF0000', '#00FF00', '#0000FF'],
        useAsButton: true,
        components: {
            preview: true,
            opacity: false,
            hue: true,
            interaction: { hex: true, input: true, save: true }
        }
    });

    pickr.on('save', (color) => {
        const hexColor = color.toHEXA().toString();
        document.getElementById('color_picker').value = hexColor;
        document.getElementById('colorBox').style.background = hexColor;
        document.getElementById('colorValue').textContent = hexColor;
        pickr.hide();
    });
</script>

@endsection