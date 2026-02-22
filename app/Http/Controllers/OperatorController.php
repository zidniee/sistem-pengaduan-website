<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Validation\Rule;
use App\Models\Platforms;
use App\Models\Complaints;
use App\Models\Inspections;
use App\Models\DailyFact;
use App\Models\MonthlySnapshot;
use App\Models\YearlySnapshot;
use App\Models\ImportLog;
use App\Jobs\ImportLaporanJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    /**
     * Display the operator dashboard with complaint statistics.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard() {
        $complaints = Complaints::with(['latestInspection'])->get();
        $Newcomplaints = Complaints::with(['latestInspection'])->take(10)->latest()->get();
        $totalComplaints = Complaints::count();
        $processingComplaints = Complaints::whereHas('latestInspection', function ($query) {
            $query->where('new_status', 'sedang-diproses');
        })->count();
        $verifyingComplaints = Complaints::whereHas('latestInspection', function ($query) {
            $query->where('new_status', 'sedang-diverifikasi');
        })->count();
        $completedComplaints = Complaints::whereHas('latestInspection', function ($query) {
            $query->where('new_status', 'laporan-diterima');
        })->count();
        
        // Count by account status
        $activeAccounts = Complaints::whereHas('latestInspection', function ($query) {
            $query->where('account_status', 'Masih Aktif');
        })->count();
        
        $blockedAccounts = Complaints::whereHas('latestInspection', function ($query) {
            $query->where('account_status', 'Telah Diblokir');
        })->count();

        // Query 7 hari terakhir
        $lastDates = DailyFact::distinct()
            ->orderBy('date', 'desc')
            ->limit(7)
            ->pluck('date');
        $weekly = DailyFact::whereIn('date', $lastDates)   
            ->orderBy('date')
            ->get();

        // Query 12 bulan terakhir
        $lastMonths = MonthlySnapshot::selectRaw('DISTINCT year, month')
            ->orderByRaw('year DESC, month DESC')
            ->limit(12)
            ->get();
        $monthly = MonthlySnapshot::where(function($query) use ($lastMonths) {
        foreach ($lastMonths as $m) {
            $query->orWhere(function($q) use ($m) {
                $q->where('year', $m->year)
                ->where('month', $m->month);
            });
        }})->get();

        $yearly = YearlySnapshot::all();
        $platforms = Platforms::all();

        return view('Operator.dashboard', compact('complaints', 'Newcomplaints', 'totalComplaints', 'processingComplaints', 'verifyingComplaints', 'completedComplaints', 'activeAccounts', 'blockedAccounts', 'weekly', 'monthly', 'yearly', 'platforms'));
    }

    /**
     * Display paginated list of complaints with optional search and status filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function complaintsList(Request $request) {
        $search = $request->input('search');
        $status = $request->input('status');
        $accountStatus = $request->input('account_status');
        $perPage = $request->input('per_page', 10);
        
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        
        $currentYear = now()->year;

        // OPTIMIZATION: Cache year range selama 1 hari untuk menghindari query berulang
        $yearRange = Cache::remember('complaints_year_range', 86400, function () {
            return DB::table('complaints')
                ->selectRaw('MIN(YEAR(submitted_at)) as min_year, MAX(YEAR(submitted_at)) as max_year')
                ->first();
        });
        
        $startYear = $yearRange->min_year ?? $currentYear;
        $endYear = max($currentYear, $yearRange->max_year ?? $currentYear);

        // OPTIMIZATION: Gunakan lazy eager loading + select specific columns untuk reduce memory
        $query = Complaints::select([
                'complaints.id',
                'complaints.platform_id',
                'complaints.ticket',
                'complaints.username',
                'complaints.account_url',
                'complaints.submitted_at',
                'complaints.created_at',
                'complaints.bukti',
                'complaints.description'
            ])
            ->filterComplaints($search, $status, null, null, $accountStatus);

        $complaints = $query
            ->with(['platform' => fn($q) => $q->select('id', 'name')])
            ->with(['latestInspection' => fn($q) => $q->select('inspections.id', 'inspections.complaint_id', 'inspections.new_status', 'inspections.account_status', 'inspections.inspected_at')])
            ->latest('complaints.created_at')
            ->paginate($perPage)
            ->withQueryString();

        // OPTIMIZATION: Fetch all platforms for modal dropdown (from cache jika available)
        $platforms = Platforms::select('id', 'name')->get();
        
        // Initialize complaint variable for modal (populated dynamically via JavaScript)
        $complaint = null;

        return view('Operator.complaint-list', compact('complaints', 'search', 'status', 'accountStatus', 'platforms', 'complaint', 'startYear', 'endYear', 'perPage'));
    }

    /**
     * Fetch and display complaints submitted today.
     *
     * @return \Illuminate\View\View
     */
    public function fetchDailyReport() {
        $complaints = Complaints::with('platform')->whereDate('created_at', Carbon::today())
            ->paginate(10);
        
        return view('Operator.daily-reports', compact('complaints'));
    }

    /**
     * Display detailed information for a specific complaint.
     *
     * @param string $encryptedId Encrypted complaint ID
     * @return \Illuminate\View\View
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function complaintDetail($encryptedId) {
        try {
            $id = decrypt($encryptedId);
            $complaint = Complaints::with(['platform','latestInspection'])->findOrFail($id);
            
            return view('Operator.complaint-detail', compact('complaint'));
        } catch (DecryptException $e) {
            abort(400, 'ID terenkripsi tidak valid'); 
        } catch (ModelNotFoundException $e) {
            abort(404, 'Aduan tidak ditemukan');
        } catch (\Exception $e) {
            abort(500, 'Terjadi kesalahan server');
        }
    }

    /**
     * Update complaint details and create inspection record if status changes.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $encryptedId Encrypted complaint ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateComplaint(Request $request, $encryptedId) {
        try {
            $id = decrypt($encryptedId);
            $complaint = Complaints::with('latestInspection')->findOrFail($id);
        } catch (\Exception $e) {
            return redirect()->route('complaint-list')
                ->with('error', 'Aduan tidak ditemukan');
        }
        
        $validated = $request->validate([
            'account_status' => 'nullable|in:Masih Aktif,Telah Diblokir',
            'new_status' => 'nullable|in:sedang-diproses,sedang-diverifikasi,laporan-diterima,ditolak',
            'description' => 'nullable|string|max:1000',
            'checked_at' => 'nullable|date|before_or_equal:today',
            'ticket' => 'nullable|string|max:255',
        ], [
            'new_status.in' => 'Status tidak valid',
            'description.max' => 'Deskripsi terlalu panjang (maksimal 1000 karakter)',
            'checked_at.before_or_equal' => 'Tanggal cek tidak boleh lebih dari hari ini',
            'ticket.max' => 'Tiket terlalu panjang (maksimal 255 karakter)',
        ]);

        try {
            // Filter and update only non-null validated fields
            $updateData = array_filter(
                Arr::only($validated, ['description', 'ticket']),
                fn($value) => !is_null($value) && $value !== ''
            );
            
            if (!empty($updateData)) {
                $complaint->update($updateData);
            }

            // Create new inspection record if status has changed
            if (!empty($validated['new_status'])) {
                $oldStatus = $complaint->latestInspection?->new_status ?? 'sedang-diproses';
                Inspections::create([
                    'complaint_id' => $complaint->id,
                    'user_id' => Auth::id() ?? $complaint->user_id,
                    'old_status' => $oldStatus,
                    'new_status' => $validated['new_status'],
                    'account_status' => $validated['account_status'] ?? 'Masih Aktif',
                    'ticket' => $complaint->ticket,
                    'inspected_at' => $validated['checked_at'] ?? now(),
                ]);
            } 
            return redirect()->route('complaint-list')
                ->with('success', 'Data aduan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($request->all())
                ->with('error', 'Gagal memperbarui aduan: ' . $e->getMessage());
        }
    }

    /**
     * Add a new complaint with evidence file upload.
     *
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComplaint(Request $req) {
        $req->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'platform' => 'required|exists:platforms,id',
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date|before_or_equal:today',
            'url' => 'required|string|max:500', // url terlalu strict
            'alasan' => 'required|string|min:10|max:1000',
        ]);

        try {
            $complaints = new Complaints();
            $complaints->user_id = Auth::id();
            $complaints->platform_id = $req->platform;
            $complaints->username = $req->nama;
            $complaints->submitted_at = $req->tanggal;
            $complaints->account_url = $req->url;
            $complaints->description = $req->alasan;

            // Generate filename using the same convention as LaporanImport
            $extension = $req->bukti->getClientOriginalExtension();
            $usernameSlug = Str::slug($req->nama, '-');
            $imagename = $usernameSlug.'.'.$extension;
            $complaints->bukti = $imagename;

            // Save complaint to database
            $complaints->save();
            
            // Store uploaded evidence file
            $req->bukti->storeAs('submissions', $imagename, 'local');

            return redirect()->back()->with('addSuccess', 'Laporan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($req->all())
                ->with('addError', 'Gagal menambahkan laporan. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }

    /**
     * Display list of all available platforms.
     *
     * @return \Illuminate\View\View
     */
    public function showPlatforms() {
        $platforms = Platforms::all();
        return view('Operator.platforms-list', compact('platforms'));
    }

    /**
     * Download evidence image for a complaint.
     *
     * @param string $encryptedId Encrypted complaint ID
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function downloadEvidence($encryptedId) {
        try {
            $id = decrypt($encryptedId);
            $complaint = Complaints::findOrFail($id);

            // Check if evidence exists
            if (!$complaint->bukti) {
                abort(404, 'Bukti tidak tersedia.');
            }

            $path = 'submissions/' . $complaint->bukti;
            
            // Check if file exists in storage
            if (!\Storage::disk('local')->exists($path)) {
                abort(404, 'File bukti tidak ditemukan.');
            }

            // Generate download filename with ticket number for better identification
            $extension = pathinfo($complaint->bukti, PATHINFO_EXTENSION);
            $downloadName = 'Bukti_' . ($complaint->ticket ?? 'Laporan_' . $complaint->id) . '.' . $extension;

            return \Storage::disk('local')->download($path, $downloadName);
        } catch (DecryptException $e) {
            abort(404, 'ID tidak valid.');
        } catch (ModelNotFoundException $e) {
            abort(404, 'Laporan tidak ditemukan.');
        }
    }

    /**
     * Display evidence image for a complaint.
     *
     * @param string $encryptedId Encrypted complaint ID
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function showEvidence($encryptedId) {
        try {
            $id = decrypt($encryptedId);
            $complaint = Complaints::findOrFail($id);

            if (!$complaint->bukti) {
                abort(404, 'Bukti tidak tersedia.');
            }

            $path = 'submissions/' . $complaint->bukti;

            if (!Storage::disk('local')->exists($path)) {
                abort(404, 'File bukti tidak ditemukan.');
            }

            return Storage::disk('local')->response($path);
        } catch (DecryptException $e) {
            abort(404, 'ID tidak valid.');
        } catch (ModelNotFoundException $e) {
            abort(404, 'Laporan tidak ditemukan.');
        }
    }

    /**
     * Create a new platform with validation.
     *
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPlatform(Request $req) {
        // TODO update validasi agar tidak terlalu strict
        $req->validate([
            'nama_platform' => 'required|string|max:255',Rule::unique('platforms, name')->ignore(new Platforms()),
            'url_platform' => 'required|string|max:500',
            'warna_platform' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'nama_platform.required' => 'Nama platform wajib diisi.',
            'nama_platform.unique' => 'Nama platform sudah ada.',
            'nama_platform.max' => 'Nama platform maksimal 255 karakter.',
            'url_platform.required' => 'URL platform wajib diisi.',
            'url_platform.max' => 'URL platform maksimal 500 karakter.',
        ]);

        try {
            $platform = new Platforms();
            $platform->name = $req->nama_platform;
            $platform->url = $req->url_platform;
            $platform->warna = $req->warna_platform;
            $platform->save();

            return redirect()->back()->with('success', 'Platform berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($req->all())
                ->with('error', 'Gagal menambahkan platform. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }

    /**
     * Delete a platform by ID.
     *
     * @param int $id Platform ID
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deletePlatform($id) {
        try {
            $platform = Platforms::findOrFail($id);
            $platform->delete();

            return redirect()->back()->with('success', 'Platform berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Platform tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus platform. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }

    /**
     * Generate and download PDF report of complaints with optional filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function generatePDFReport(Request $request)
    {
        try {
            // Retrieve filter parameters
            $search = $request->input('search');
            $status = $request->input('status');
            $semester = $request->input('semester');
            $month = $request->input('month');

            // Fetch complaints with filters applied (limit to 1000 records)
            $complaints = Complaints::with(['platform','latestInspection'])
                ->filterComplaints($search, $status, $semester, $month)
                ->orderBy('submitted_at', 'desc')
                ->limit(1000)
                ->latest()
                ->get();
            
            // Generate PDF with landscape A4 format
            $pdf = Pdf::loadview('Operator.partials.pdf-report-modern', compact('complaints', 'search', 'status', 'semester', 'month'))
                ->setPaper('a4', 'landscape')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true);
            
            // Generate timestamped filename
            $filename = 'Laporan_Audit_'.date('Y-m-d_His').'.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghasilkan laporan PDF: '.$e->getMessage());
        }
    }

    /**
     * Display the import form for bulk complaint uploads.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('Operator.import-reports');
    }

    /**
     * Import complaints from Excel file in background queue.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importLaporan(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:5120',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes' => 'Format file harus .xlsx.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Store uploaded file temporarily
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('imports/temp', $filename);

        // Create import log
        $importLog = ImportLog::create([
            'user_id' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        // Dispatch job to queue
        ImportLaporanJob::dispatch($importLog->id, $filePath)
            ->onQueue('imports');

        return redirect()->route('laporan.import.history')
            ->with('import_success', 'File berhasil diunggah. Import sedang diproses di background. Anda dapat melihat progress di halaman ini.');
    }

    /**
     * Display import history with status.
     *
     * @return \Illuminate\View\View
     */
    public function importHistory()
    {
        $imports = ImportLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Operator.import-history', compact('imports'));
    }

    /**
     * Get import status via AJAX.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImportStatus($id)
    {
        $importLog = ImportLog::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'status' => $importLog->status,
            'total_rows' => $importLog->total_rows,
            'success_count' => $importLog->success_count,
            'failed_count' => $importLog->failed_count,
            'failed_rows' => $importLog->failed_rows,
            'error_message' => $importLog->error_message,
            'started_at' => $importLog->started_at,
            'completed_at' => $importLog->completed_at,
            'progress_percentage' => $importLog->getProgressPercentage(),
        ]);
    }

    /**
     * Generate and download Excel template for complaint import.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadImportTemplate()
    {
        return response()->streamDownload(function () {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Define column headers for import template
            $headers = ['nama_akungrup', 'link', 'tanggal', 'tiket', 'tanggal_tracking', 'status', 'account_status', 'bukti'];

            // Populate header row
            foreach ($headers as $index => $header) {
                $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
            }

            // Apply styling to header row
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']],
            ];

            $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

            // Auto-size all columns
            foreach (range('A', 'H') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Set header row height
            $sheet->getRowDimension(1)->setRowHeight(20);

            // Write Excel file to output stream
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'Template_Import_Laporan.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
