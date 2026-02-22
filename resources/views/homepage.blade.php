@extends('layouts.master')
@section('content')

<section class="relative w-full min-h-screen flex items-center overflow-hidden">
    
    <div class="absolute inset-0">
        <img src="{{ asset('images/background-hero.png') }}" alt="Kota Surakarta" class="w-full h-full object-cover object-center">
        
        <div class="absolute inset-0 bg-gradient-to-r from-[#0f172a]/80 via-[#1e293b]/50 to-[#2E86AB]/20 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent opacity-80"></div>
    </div>

    <div class="relative w-full h-full flex items-center z-10">
        <div class="max-w-7xl mx-auto px-6 md:px-10 w-full pt-20"> 
            <div class="max-w-4xl space-y-8">
                
                <p class="text-sm md:text-base font-bold text-[#F59E0B] tracking-[0.25em] uppercase border-l-4 border-[#F59E0B] pl-4 mb-2">
                    Diskominfo SP Kota Surakarta
                </p>

                <div class="space-y-2">
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight tracking-tight drop-shadow-lg">
                        Portal Pengaduan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Konten Negatif</span>
                    </h1>
                </div>

                <p class="text-base md:text-xl text-blue-100/80 max-w-2xl leading-relaxed font-light border-l border-white/10 pl-4">
                    Platform resmi dan terpercaya untuk partisipasi warga. Kami memastikan setiap laporan ditangani dengan standar keamanan data, profesionalitas, dan kerahasiaan penuh.
                </p>
                
                {{-- CTA buttons --}}
                <div class="flex flex-wrap items-center gap-6 pt-6">
                    <a href="{{ route('reports') }}" class="group relative inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#F59E0B] hover:bg-[#fbbf24] text-[#0f172a] text-base md:text-lg font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-orange-500/40 hover:-translate-y-1">
                        <span>Laporkan Temuan Kalian</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Efek Dekoratif Blur --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#2E86AB]/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-[#F59E0B]/10 rounded-full blur-[80px] pointer-events-none"></div>

</section>

<!-- Panduan Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-[#0f172a] via-[#102a63] to-[#0b1f4b] py-16 md:py-24 flex items-center">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 -right-32 h-96 w-96 rounded-full bg-[#2E86AB]/30 blur-[100px]"></div>
        <div class="absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-[#F59E0B]/20 blur-[100px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.1),transparent_50%)]"></div>
    </div>

    <div class="relative w-full max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24">
        <div class="max-w-4xl space-y-8">
            <div class="animate-fade-in">
                <p class="text-xs md:text-sm font-bold text-[#F59E0B] tracking-[0.35em] uppercase mb-4">
                    Panduan Penggunaan
                </p>
                <h1 class="text-4xl md:text-7xl font-extrabold text-white leading-[1.1] mb-6">
                    Cara Ajukan Laporan <br class="hidden md:block"> & Cek Status
                </h1>
                <p class="text-lg md:text-2xl text-slate-300 leading-relaxed max-w-3xl">
                    Ikuti langkah singkat di bawah untuk melaporkan konten negatif dan melacak progres laporan Anda secara real-time.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            
            <div class="flex flex-col bg-white border border-slate-200 rounded-3xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-10">
                <div class="flex-grow">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#2E86AB]/10 text-[#2E86AB] font-bold text-xl">1</span>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Ajukan Laporan</h2>
                    </div>
                    
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Pastikan informasi yang Anda kirim lengkap agar proses verifikasi oleh tim kami dapat berjalan lebih cepat.
                    </p>

                    <ul class="space-y-5 text-slate-700">
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#F59E0B]"></div>
                            <span class="text-sm md:text-base text-slate-700">Buka halaman <strong>Ajukan Laporan</strong> dan isi data diri pelapor.</span>
                        </li>
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#F59E0B]"></div>
                            <span class="text-sm md:text-base text-slate-700">Lampirkan tautan (URL) atau nama platform tempat konten negatif ditemukan.</span>
                        </li>
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#F59E0B]"></div>
                            <span class="text-sm md:text-base text-slate-700">Tulis deskripsi kronologi kejadian secara singkat dan jelas.</span>
                        </li>
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#F59E0B]"></div>
                            <span class="text-sm md:text-base text-slate-700">Unggah bukti pendukung berupa tangkapan layar (screenshot).</span>
                        </li>
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#F59E0B]"></div>
                            <span class="text-sm md:text-base text-slate-700">Kirim laporan dan <strong>simpan kode unik</strong> yang muncul untuk pengecekan.</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-10 pt-6 border-t border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-4">Lampiran Wajib</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">Tautan Konten</span>
                        <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">Screenshot</span>
                        <span class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">Uraian Kejadian</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-white border border-slate-200 rounded-3xl shadow-sm hover:shadow-md transition-shadow p-6 md:p-10">
                <div class="flex-grow">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#2E86AB]/10 text-[#2E86AB] font-bold text-xl">2</span>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight">Cek Status Laporan</h2>
                    </div>
                    
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Anda dapat memantau sejauh mana laporan Anda ditindaklanjuti secara transparan melalui sistem kami.
                    </p>

                    <ul class="space-y-5 text-slate-700">
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#2E86AB]"></div>
                            <span class="text-sm md:text-base text-slate-700">Klik tombol <strong>Cek Status</strong> pada navigasi atas atau tombol di halaman ini.</span>
                        </li>
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#2E86AB]"></div>
                            <span class="text-sm md:text-base text-slate-700">Masukkan kode laporan unik dan identitas pelapor yang didaftarkan.</span>
                        </li>
                        <li class="flex gap-4">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#2E86AB]"></div>
                            <span class="text-sm md:text-base text-slate-700">Sistem akan menampilkan status terbaru: <strong>Verifikasi</strong>, <strong>Proses</strong>, atau <strong>Selesai</strong>.</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-10 p-5 rounded-2xl bg-[#2E86AB]/5 border border-[#2E86AB]/10">
                    <div class="flex gap-3">
                        <svg class="h-5 w-5 text-[#2E86AB] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="text-xs font-bold text-[#2E86AB] uppercase tracking-wider mb-1">Tips Keamanan</p>
                            <p class="text-xs text-slate-600 leading-normal">Pastikan kode laporan tidak dibagikan kepada pihak lain untuk menjaga privasi data Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 md:mt-16 rounded-[2rem] bg-[#0f172a] p-8 md:p-12 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition-transform">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
            </div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
                <div class="space-y-4">
                    <h3 class="text-2xl md:text-3xl font-bold text-white">Butuh bantuan lebih lanjut?</h3>
                    <p class="text-slate-400 text-base md:text-lg max-w-xl">
                        Tim kami siap membantu jika Anda mengalami kendala teknis dalam pengaduan.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <a href="mailto:pengaduan@surakarta.go.id" class="px-8 py-3.5 rounded-xl bg-white text-[#0f172a] font-bold hover:bg-slate-100 transition-colors text-center">
                        Hubungi Email
                    </a>
                    <a href="{{ route('reports') }}" class="px-8 py-3.5 rounded-xl bg-slate-800 text-white font-bold border border-slate-700 hover:bg-slate-700 transition-colors text-center">
                        Buka Tiket Bantuan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection