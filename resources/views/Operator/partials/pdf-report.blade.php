<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Audit Aduan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10pt;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background-color: #4a5568;
            color: white;
        }
        
        th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #000;
        }
        
        td {
            padding: 6px 5px;
            font-size: 8pt;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }
        
        tbody tr:hover {
            background-color: #edf2f7;
        }
        
        .text-center {
            text-align: center;
        }
        
        .link-cell {
            word-break: break-all;
            max-width: 150px;
            font-size: 7pt;
        }
        
        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            display: inline-block;
        }
        
        .status-diterima {
            background-color: #c6f6d5;
            color: #22543d;
        }
        
        .status-diproses {
            background-color: #fef5e7;
            color: #7c2d12;
        }
        
        .status-diverifikasi {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-ditolak {
            background-color: #fed7d7;
            color: #742a2a;
        }
        
        .footer {
            margin-top: 20px;
            font-size: 8pt;
            color: #666;
            text-align: right;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AUDIT ADUAN</h1>
        <p>Sistem Pengaduan Web Prostitusi</p>
        @if(isset($month) && $month)
            @php
                $monthPeriod = \Carbon\Carbon::createFromFormat('Y-m', $month);
            @endphp
            <p style="font-weight: bold; font-size: 10pt; margin-top: 5px;">Periode: {{ $monthPeriod->translatedFormat('F Y') }}</p>
        @elseif(isset($semester) && $semester)
            @php
                list($year, $sem) = explode('-', $semester);
                $semesterText = $sem == 1 ? 'Januari - Juni' : 'Juli - Desember';
            @endphp
            <p style="font-weight: bold; font-size: 10pt; margin-top: 5px;">Periode: Semester {{ $sem }} {{ $year }} ({{ $semesterText }})</p>
        @endif
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
        @if($search || $status || (isset($month) && $month))
            <p style="font-size: 9pt; margin-top: 5px;">
                Filter: 
                @if($search) <strong>Pencarian: {{ $search }}</strong> @endif
                @if($status) <strong>Status: {{ ucwords(str_replace('-', ' ', $status)) }}</strong> @endif
                @if(isset($month) && $month) <strong>Bulan: {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</strong> @endif
            </p>
        @endif
    </div>

    @if($complaints->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 3%;">No</th>
                <th style="width: 15%;">Nama Akun/Grup</th>
                <th style="width: 20%;">Link</th>
                <th class="text-center" style="width: 10%;">Tanggal</th>
                <th class="text-center" style="width: 10%;">Tiket</th>
                <th class="text-center" style="width: 10%;">Tanggal Tracking</th>
                <th style="width: 12%;">Status Aduan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $index => $complaint)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $complaint->username }}</td>
                <td class="link-cell">{{ $complaint->account_url }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($complaint->submitted_at)->format('d M Y') }}</td>
                <td class="text-center">{{ $complaint->ticket }}</td>
                <td class="text-center">
                    @if($complaint->latestInspection)
                        {{ \Carbon\Carbon::parse($complaint->latestInspection->inspected_at)->format('d M Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
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
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        Tidak ada data aduan yang tersedia.
    </div>
    @endif

    <div class="footer">
        <p>Total aduan: {{ $complaints->count() }}</p>
        <p>Laporan ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>