<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Audit Aduan</title>
    <style>
        /* Margin Kertas */
        @page {
            margin: 20px 25px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8pt; 
            line-height: 1.3;
            color: #333;
        }
    
        .kop-surat {
            width: 100%;
            border-bottom: none;
            margin-bottom: 0;
            margin-top: 0;
        }
        .kop-surat td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-logo {
            width: 15%;
            text-align: center;
        }
        .kop-logo img {
            width: 75px;
            height: auto;
        }
        .kop-text {
            width: 70%;
            text-align: center;
            line-height: 1.2;
        }
        .kop-text .instansi {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text .dinas {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
        }
        .kop-text .alamat {
            font-size: 8pt;
            margin-top: 4px;
        }
        .kop-text .kontak {
            font-size: 8pt;
        }
        
        .kop-line {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .report-title-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title-container h1 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .report-title-container p {
            font-size: 9pt;
            margin-bottom: 2px;
        }

        .data-table {
            width: 100%;
            table-layout: fixed; 
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .data-table thead {
            background-color: #2E86AB; 
            color: #ffffff;
        }
        
        .data-table th {
            padding: 8px 4px;
            font-size: 8pt;
            font-weight: bold;
            border: 1px solid #1f5a7a;
            vertical-align: middle;
            text-align: center;
        }
        
        .data-table td {
            padding: 8px 6px; 
            font-size: 7.5pt;
            border: 1px solid #cbd5e0;
            vertical-align: middle; 
            page-break-inside: avoid;
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc; 
        }
        
        .text-center {
            text-align: center;
        }
        
        .link-cell {
            color: #2b6cb0;
            font-size: 7pt; 
            line-height: 1.4;
            text-decoration: none;
        }
        
        /* Badge Status */
        .status-badge {
            padding: 4px 8px; 
            border-radius: 4px;
            font-size: 6.5pt;
            font-weight: bold;
            display: inline-block; 
            text-align: center;
            text-transform: uppercase; 
            min-width: 55px; 
            white-space: nowrap;
        }
        
        .status-diterima { background-color: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .status-diproses { background-color: #fef5e7; color: #7c2d12; border: 1px solid #fbd38d; }
        .status-diverifikasi { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .status-ditolak { background-color: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
        .status-aktif { background-color: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .status-diblokir { background-color: #fed7d7; color: #742a2a; border: 1px solid #feb2b2; }
        
        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 8pt;
            color: #718096;
            text-align: right;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('images/dinas-surakarta.png') }}" alt="Logo Diskominfo">
            </td>
            
            <td class="kop-text">
                <div class="instansi">PEMERINTAH KOTA SURAKARTA</div>
                <div class="dinas">DINAS KOMUNIKASI, INFORMATIKA, STATISTIK DAN PERSANDIAN</div>
                <div class="alamat">Dinas Kependudukan Dan Pencatatan Sipil (Kampung Baru), Kp. Baru, Kec. Ps. Kliwon, Kota Surakarta, Jawa Tengah 57133</div>
                <div class="kontak">Telepon: (0271) 123456 | Email: diskominfo@surakarta.go.id</div>
            </td>
            
            <td class="kop-logo">
            </td>
        </tr>
    </table>
    
    <div class="kop-line"></div>

    <div class="report-title-container">
        <h1>LAPORAN AUDIT ADUAN KONTEN NEGATIF</h1>
        <p>Sistem Pengaduan Web Prostitusi</p>
        
        @if(isset($semester) && $semester)
            @php
                list($year, $sem) = explode('-', $semester);
                $semesterText = $sem == 1 ? 'Januari - Juni' : 'Juli - Desember';
            @endphp
            <p style="font-weight: bold; margin-top: 4px;">Periode: Semester {{ $sem }} Tahun {{ $year }} ({{ $semesterText }})</p>
        @endif
        
        <p style="font-style: italic; color: #4a5568;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    @if($complaints->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 16%; text-align: left;">Nama Akun/Grup</th>
                <th style="width: 32%; text-align: left;">Link (URL)</th> 
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 10%;">Tiket</th>
                <th style="width: 8%;">Tgl Track</th>
                <th style="width: 11%;">Status Aduan</th>
                <th style="width: 11%;">Status Akun</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $index => $complaint)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $complaint->username }}</td>
                
                <td>
                    @php
                        $url = $complaint->account_url ?? '-';
                        $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                        $wrappedUrl = implode('<br>', str_split($safeUrl, 50));
                    @endphp
                    <div class="link-cell">
                        {!! $wrappedUrl !!}
                    </div>
                </td>
                
                <td class="text-center">{{ \Carbon\Carbon::parse($complaint->submitted_at)->format('d M y') }}</td>
                <td class="text-center">{{ $complaint->ticket ?? '-' }}</td>
                <td class="text-center">
                    @if($complaint->latestInspection && $complaint->latestInspection->inspected_at)
                        {{ \Carbon\Carbon::parse($complaint->latestInspection->inspected_at)->format('d M y') }}
                    @else
                        -
                    @endif
                </td>
                
                <td class="text-center">
                    @if($complaint->latestInspection)
                        @php
                            $status = $complaint->latestInspection->new_status;
                            $statusClass = match($status) {
                                'laporan-diterima' => 'status-diterima',
                                'sedang-diproses' => 'status-diproses',
                                'sedang-diverifikasi' => 'status-diverifikasi',
                                'ditolak' => 'status-ditolak',
                                default => ''
                            };
                            $statusText = match($status) {
                                'laporan-diterima' => 'Laporan diterima',
                                'sedang-diproses' => 'Sedang diproses',
                                'sedang-diverifikasi' => 'Sedang diverifikasi',
                                'ditolak' => 'Ditolak',
                                default => $status
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                    @else
                        <span class="status-badge status-diproses">Belum diproses</span>
                    @endif
                </td>
                
                <td class="text-center">
                    @if($complaint->latestInspection)
                        @php
                            $accStatus = $complaint->latestInspection->account_status;
                            $isBlocked = ($accStatus === 'Telah Diblokir');
                        @endphp
                        <span class="status-badge {{ $isBlocked ? 'status-diblokir' : 'status-aktif' }}">
                            {{ $isBlocked ? 'Telah Diblokir' : 'Masih Aktif' }}
                        </span>
                    @else
                        <span class="status-badge status-aktif">Masih Aktif</span>
                    @endif
                </td> 
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 30px; border: 1px dashed #cbd5e0; margin-top: 20px; color: #a0aec0;">
        Tidak ada data aduan yang sesuai dengan filter yang dipilih.
    </div>
    @endif

    <div class="footer">
        <p>Total Laporan: <strong>{{ $complaints->count() }} Data</strong></p>
        <p>Sistem Pengaduan Konten Negatif Diskominfo SP Kota Surakarta</p>
    </div>
</body>
</html>