@extends('layouts.admin-dashboard')

@section('dashboard-content')
<div class="-m-6 p-8 min-h-screen bg-slate-50 relative overflow-x-hidden">
    {{-- Background Assets --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.08]" 
        style="background-image: url('{{ asset('images/Final-Pattern.png') }}'); background-repeat: repeat; background-size: 600px auto;">
    </div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#2E86AB]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#F59E0B]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto">
        
        {{-- 1. HEADER (Langsung Tampil) --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#0f172a]">Dashboard Overview</h1>
                <p class="text-slate-500 mt-1">Selamat datang kembali, <span class="font-semibold text-[#2E86AB]">{{ Auth::user()->name }}</span>!</p>
            </div>
            <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 text-sm text-slate-500 shadow-sm">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- 2. SUMMARY CARDS SECTION --}}
        
        {{-- Skeleton Cards --}}
        <div id="cards-skeleton" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 opacity-100 transition-opacity duration-500 ease-in-out">
            @for ($i = 1; $i <= 6; $i++)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-32">
                <div class="flex justify-between">
                    <div class="space-y-3 w-full">
                        <div class="h-3 bg-slate-200 rounded w-24"></div>
                        <div class="h-8 bg-slate-300 rounded w-16"></div>
                        <div class="h-3 bg-slate-200 rounded w-32"></div>
                    </div>
                    <div class="w-12 h-12 bg-slate-200 rounded-lg"></div>
                </div>
            </div>
            @endfor
        </div>

        <div id="cards-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 hidden opacity-0 transition-opacity duration-500 ease-in-out">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Aduan</p>
                        <h3 class="text-3xl font-bold text-[#0f172a] mt-2">{{ $totalComplaints ?? 0 }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Semua laporan masuk</p>
                    </div>
                    <div class="w-12 h-12 bg-[#2E86AB]/10 rounded-lg flex items-center justify-center text-[#2E86AB] group-hover:bg-[#2E86AB] group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diproses</p>
                        <h3 class="text-3xl font-bold text-[#0f172a] mt-2">{{ $processingComplaints ?? 0 }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Sedang ditindaklanjuti</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diverifikasi</p>
                        <h3 class="text-3xl font-bold text-[#0f172a] mt-2">{{ $verifyingComplaints ?? 0 }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Dalam tahap verifikasi</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diterima</p>
                        <h3 class="text-3xl font-bold text-[#0f172a] mt-2">{{ $completedComplaints ?? 0 }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Sukses memenuhi syarat</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Masih Aktif</p>
                        <h3 class="text-3xl font-bold text-[#0f172a] mt-2">{{ $activeAccounts ?? 0 }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Akun aktif</p>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all relative overflow-hidden group">
                <div class="flex items-start justify-between relative z-10">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diblokir</p>
                        <h3 class="text-3xl font-bold text-[#0f172a] mt-2">{{ $blockedAccounts ?? 0 }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Akun diblokir</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
        {{-- Skeleton Chart --}}
        <div id="chart-skeleton" class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 md:p-8 h-[500px] animate-pulse">
            <div class="flex justify-between items-center mb-8">
                <div class="space-y-2">
                    <div class="h-6 bg-slate-300 rounded w-48"></div>
                    <div class="h-4 bg-slate-200 rounded w-32"></div>
                </div>
                <div class="h-10 bg-slate-200 rounded w-32"></div>
            </div>
            <div class="w-full h-[350px] bg-slate-100 rounded-lg"></div>
        </div>

        {{-- Real Content Chart --}}
        <div id="chart-content" class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 md:p-8 hidden opacity-0 transition-opacity duration-500 ease-in-out">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Laporan Statistik</h3>
                    <p class="text-gray-500 text-sm">Total pelaporan <span class="font-bold">{{ number_format($totalComplaints ?? 70500) }}</span></p>
                </div>
                <div>
                    <select id="timeRangeFilter" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50 transition outline-none cursor-pointer appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3E%3Cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3E%3C/svg%3E')] bg-[length:1.25rem_1.25rem] bg-no-repeat bg-[right_0.5rem_center]">
                        <option value="weekly" selected>Mingguan</option>
                        <option value="monthly" >Bulanan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>
            </div>
            <div class="overflow-auto">
                <div class="flex items-center gap-6 text-sm mb-6">
                    @foreach($platforms as $platform)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full inline-block" style="background-color: {{ $platform->warna }}"></span>
                        <span class="text-slate-600">{{ $platform->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="overflow-auto">
                <div class="min-w-300 w-full h-[400px]">
                    <canvas id="deliveryChart"></canvas>
                </div>
            <div>
        </div>
    </div> 
        {{-- 4. DATA TABLE (Langsung Tampil) --}}
        <div class="mt-8">
            <h2 class="text-lg font-bold text-[#0f172a] mb-4">Data Terbaru</h2>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-left">Platform</th>
                                <th class="px-6 py-3 text-left">Pelapor</th>
                                <th class="px-6 py-3 text-left">Username Terlapor</th>
                                <th class="px-6 py-3 text-left">Deskripsi</th>
                                <th class="px-6 py-3 text-left">Bukti URL</th>
                                <th class="px-6 py-3 text-left">Bukti Gambar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @foreach ($Newcomplaints as $complaint)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">{{ $complaint->platform?->name }}</td>
                                <td class="px-6 py-4">{{ $complaint->user?->name }}</td>
                                <td class="px-6 py-4 font-medium">{{ $complaint->username }}</td>
                                <td class="px-6 py-4 truncate max-w-xs">{{ $complaint->description }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ $complaint->account_url }}" target="_blank" class="text-blue-600 hover:underline">Link</a>
                                </td>
                                <td class="px-2 py-1">
                                    @if ($complaint->bukti)
                                    <img class="size-25 ring-2 ring-blue-200" src="{{ route('admin.bukti', encrypt($complaint->id)) }}"/>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Load Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. SIMULASI LOADING
        setTimeout(() => {
            // Hide Skeletons
            document.getElementById('cards-skeleton').style.display = 'none';
            document.getElementById('chart-skeleton').style.display = 'none';
            
            // Show Content with Fade In
            const cardsContent = document.getElementById('cards-content');
            const chartContent = document.getElementById('chart-content');
            
            cardsContent.classList.remove('hidden');
            chartContent.classList.remove('hidden');
            
            // Trigger reflow for transition
            void cardsContent.offsetWidth; 
            
            cardsContent.classList.remove('opacity-0');
            chartContent.classList.remove('opacity-0');

            // Initialize Chart AFTER showing container (important for canvas size)
            initChart();

        }, 1500);

        // 2. CHART LOGIC

        let platforms = Object.values(@json($platforms));

        let weekly = Object.values(@json($weekly));
        let monthly = Object.values(@json($monthly));
        let yearly = Object.values(@json($yearly));

        function initChart() {
            const ctx = document.getElementById('deliveryChart').getContext('2d');
            const timeRangeFilter = document.getElementById('timeRangeFilter');

            const chartData = {
                weekly: {
                    labels: [],
                    datas: []
                },
                monthly: {
                    labels: [],
                    datas: []
                },
                yearly: {
                    labels: [],
                    datas: []
                }
            };

            // Initialize datas
            const allDates = Array.from({ length: 7 }, (_, i) => {
              const d = new Date();
              d.setDate(d.getDate() - i);
              return d.toISOString().split('T')[0];
            }).reverse();
            const allMonths = new Map();
            monthly.forEach(s =>{
                const key = `${s.year}-${s.month}`;
                if(!allMonths.has(key)) {
                    allMonths.set(key, `${s.month_name} ${s.year}`);
                }
            });
            const allYears = [...new Set(yearly.map(s => s.year))].sort();

            // Kelompokkan hari, bulan, tahun berdasarkan platform
            const groupedData = platforms.map(platform => ({
                dates: allDates.map(date => {
                    const snapshot = weekly.find(s => s.platform_id === platform.id && s.date === date);
                    console.log(typeof(weekly[0].date));
                    return snapshot ? snapshot.dikirim : 0;
                }),
                months: Array.from(allMonths.keys()).map((key) => {
                    const [year, month] = key.split('-');
                    const monthData = monthly.find(s => s.platform_id === platform.id && s.year == year && s.month == month);
                    return monthData ? monthData.dikirim_total : 0;
                }),
                years: allYears.map(year => {
                    const yearData = yearly.find(s => s.platform_id === platform.id && s.year === year);
                    return yearData ? yearData.dikirim_total : 0;
                    
                })
            }));

            // Push ke chartData
            // Push label (sekali)
            chartData.weekly.labels.push(...allDates);
            chartData.monthly.labels.push(...allMonths.values());
            chartData.yearly.labels.push(...allYears);
            // Push data
            for (let i = 0; i < groupedData.length; i++) {
                chartData.weekly.datas.push (groupedData[i].dates);
                chartData.monthly.datas.push (groupedData[i].months);
                chartData.yearly.datas.push (groupedData[i].years);
            }

            let myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.weekly.labels,
                    datasets: platforms.map((data, index) => ({ // Tambahkan dataset secara dinamis, tergantung jumlah platform
                        label: data.name,
                        data: chartData.weekly.datas[index],
                        backgroundColor: data.warna,
                        borderRadius: 4,
                        barPercentage: 0.5,
                        categoryPercentage: 0.8
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, max: Math.ceil(Math.max(...chartData.weekly.datas.flat()) * 1.1), grid: { color: '#f1f5f9', drawBorder: false }, ticks: { stepSize: 20 } },
                        x: { grid: { display: false } }
                    },
                    layout: { padding: { top: 20 } }
                }
            });

            timeRangeFilter.addEventListener('change', function() {
                const data = chartData[this.value];
                if (data) {
                    myChart.data.labels = data.labels;
                    // Ambil dataset berdasarkan jumlah platform yang ada
                    for (let i = 0; i < platforms.length; i++) {
                        myChart.data.datasets[i].data = data.datas[i];
                    }
                    // Ubah nilai maxi grafik sesuai dengan nilai maksimal pada data
                    myChart.options.scales.y.max = Math.ceil(data.datas.flat()) * 1.1;
                    myChart.update();
                }
            });
        }
    });
</script>
@endsection