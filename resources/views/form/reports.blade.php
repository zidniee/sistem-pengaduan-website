@extends('layouts.master')
@section('content')

<section class="relative w-full min-h-screen flex items-center justify-center py-24 bg-[#f8fafc] overflow-hidden">
    <div class="absolute inset-0 z-0 pointer-events-none opacity-10" 
         style="background-image: url('{{ asset('images/Final-Pattern.png') }}'); background-repeat: repeat; background-size: 700px auto;">
    </div>

    <div class="relative z-10 max-w-5xl w-full px-6">
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div id="toast-container" class="fixed bottom-6 right-6 z-50 space-y-3 pointer-events-none"></div>
            <div id="toast-message" data-message="{{ session('success') }}"></div>
            <div id="toast-error" data-message="{{ isset($errors) ? $errors->first() : '' }}"></div>
            
            <div class="px-8 py-6 border-b-4 border-[#F59E0B]">
                <h2 class="text-2xl md:text-3xl font-bold text-[#2E86AB] tracking-wide">Form Laporan</h2>
                <p class="text-[#2E86AB] text-sm mt-1">Isi data laporan dengan lengkap dan valid.</p>
            </div>
            
            <div class="p-8 md:p-10">
                <form action="{{ route('submitComplaints') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col md:flex-row w-full gap-6 md:gap-10">
                        <div class="space-y-6 w-full md:w-1/2">
                            @include('layouts.form.section1')
                        </div>
                        <div class="hidden md:flex items-stretch">
                            <div class="w-px bg-slate-200"></div>
                        </div>
                        <div class="space-y-6 w-full md:w-1/2">
                            @include('layouts.form.section2')
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-4">
                        <button type="button" onclick="window.history.back()" class="px-6 py-3 text-slate-600 font-medium hover:text-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-3 bg-[#0f172a] text-white font-bold rounded-lg hover:bg-[#1e293b] focus:outline-none focus:ring-2 focus:ring-[#0f172a] focus:ring-offset-2 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastMessage = document.getElementById('toast-message')?.dataset?.message;
        const toastError = document.getElementById('toast-error')?.dataset?.message;
        if (toastMessage) {
            showToast(toastMessage, 'success');
        }
        if (toastError) {
            showToast(toastError, 'error');
        }
    });

    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const isError = type === 'error';
        const borderClass = isError ? 'border-red-200' : 'border-emerald-200';
        const textClass = isError ? 'text-red-800' : 'text-emerald-800';
        const iconColor = isError ? 'text-red-600' : 'text-emerald-600';
        const titleText = isError ? 'Gagal' : 'Sukses';

        const toast = document.createElement('div');
        toast.className = `toast-item pointer-events-auto flex items-start gap-3 bg-white border ${borderClass} ${textClass} shadow-xl rounded-xl px-4 py-3 min-w-[260px] max-w-sm`;
        toast.innerHTML = `
            <div class="mt-0.5 ${iconColor}">
                ${isError
                    ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'}
            </div>
            <div class="flex-1">
                <p class="font-semibold text-sm">${titleText}</p>
                <p class="text-sm">${message}</p>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600" aria-label="Tutup" onclick="this.closest('.toast-item').remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('toast-hide');
            setTimeout(() => toast.remove(), 250);
        }, 3500);
    }

    function validateFile(input) {
        const file = input.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // MB
            const fileType = file.type;
            
            if (fileSize > 5) {
                alert('Ukuran file terlalu besar. Maksimal 5MB');
                input.value = '';
                return;
            }
            
            if (!['image/jpeg', 'image/jpg', 'image/png'].includes(fileType)) {
                alert('Format file tidak didukung. Gunakan JPG atau PNG');
                input.value = '';
                return;
            }
        }
    }
</script>

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out;
    }
</style>

@endsection