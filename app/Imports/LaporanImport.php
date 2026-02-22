<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

/**
 * Import class for processing complaint reports from Excel files.
 *
 * This class handles:
 * - Validation of headers and data from Excel files
 * - Download/copy evidence files from Google Drive or local folder
 * - Save complaint data to database
 * - Create inspection record with audit trail for status tracking
 *
 * Expected Excel format:
 * - nama_akungrup: Name of reported account/group
 * - link: URL of reported account/post
 * - tanggal: Initial report date (Y-m-d or Excel date format)
 * - tiket: Ticket/report reference number
 * - tanggal_tracking: Latest tracking/inspection date
 * - status: Report status (Sedang Diproses, Sedang Diverifikasi, Laporan Diterima, Ditolak)
 * - account_status: Account status (Masih Aktif, Telah Diblokir)
 * - bukti: Google Drive link or local filename (folder: storage/imports/bukti/)
 *
 * @package App\Imports
 */
class LaporanImport implements OnEachRow, SkipsEmptyRows, WithChunkReading, WithHeadingRow
{
    protected int $totalRows = 0;

    protected int $successCount = 0;

    protected array $failedRows = [];

    protected ?array $platformsCache = null;

    protected array $complaintsCache = [];

    protected array $pendingInspections = [];

    protected bool $headersChecked = false;

    protected bool $invalidHeader = false;

    protected array $requiredHeaders = [
        'nama_akungrup',
        'link',
        'tanggal',
        'tiket',
        'tanggal_tracking',
        'status',
        'account_status',
        'bukti',
    ];

    /**
     * Constructor - pre-load data for faster processing
     */
    public function __construct()
    {
        $this->loadPlatformsCache();
        $this->loadComplaintsCache();
    }

    /**
     * Define which row contains the column headers.
     *
     * @return int Row number containing headers
     */
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * Define the number of rows to process per chunk.
     *
     * @return int Number of rows per chunk
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * Pre-load platforms cache to avoid repeated queries.
     */
    protected function loadPlatformsCache(): void
    {
        $this->platformsCache = DB::table('platforms')
            ->select('id', 'name', 'url')
            ->get()
            ->map(function ($platform) {
                $normalizedUrl = strtolower(trim((string) $platform->url));
                $host = $this->normalizeHost($normalizedUrl);

                return [
                    'id' => (int) $platform->id,
                    'name' => (string) $platform->name,
                    'url' => $normalizedUrl,
                    'host' => $host,
                ];
            })
            ->filter(fn ($platform) => $platform['url'] !== '')
            ->values()
            ->all();
    }

    /**
     * Pre-load existing complaints to avoid repeated queries.
     */
    protected function loadComplaintsCache(): void
    {
        $complaints = DB::table('complaints')
            ->select('id', 'account_url', 'username', 'platform_id', 'ticket', 'submitted_at')
            ->get();

        foreach ($complaints as $complaint) {
            $this->complaintsCache[$complaint->account_url] = [
                'id' => $complaint->id,
                'username' => $complaint->username,
                'platform_id' => $complaint->platform_id,
                'ticket' => $complaint->ticket,
                'submitted_at' => $complaint->submitted_at,
            ];
        }
    }

    /**
     * Process each row from the Excel file.
     *
     * Validates headers and data, downloads evidence files, saves complaint data,
     * and creates inspection records with audit trail.
     *
     * @param Row $row The current row to process
     * @return void
     */
    public function onRow(Row $row): void
    {
        $values = $row->toArray();

        if (! $this->headersChecked) {
            $missing = array_filter($this->requiredHeaders, function ($header) use ($values) {
                return ! array_key_exists($header, $values);
            });

            $this->headersChecked = true;

            if (! empty($missing)) {
                $this->invalidHeader = true;
                $this->failedRows[] = [
                    'row' => 1,
                    'reason' => 'Kolom wajib tidak ditemukan: '.implode(', ', $missing),
                ];

                return;
            }
        }

        if ($this->invalidHeader) {
            return;
        }

        $namaAkun = trim((string) ($values['nama_akungrup'] ?? ''));
        $link = trim((string) ($values['link'] ?? ''));
        $tanggalRaw = $values['tanggal'] ?? null;
        $tiket = trim((string) ($values['tiket'] ?? ''));
        $tanggalTrackingRaw = $values['tanggal_tracking'] ?? null;
        $status = trim((string) ($values['status'] ?? ''));
        $accountStatus = trim((string) ($values['account_status'] ?? ''));
        $tangkapanLayar = trim((string) ($values['bukti'] ?? ''));

        if ($namaAkun === '' && $link === '' && $tanggalRaw === null) {
            return;
        }

        $this->totalRows++;

        if ($namaAkun === '' || $link === '' || $tanggalRaw === null || $tiket === '' || $tanggalTrackingRaw === null || $status === '' || $accountStatus === '' || $tangkapanLayar === '') {
            $this->failedRows[] = [
                'row' => $row->getIndex(),
                'reason' => 'Data tidak lengkap (Nama Akun/Grup, Link, Tanggal, Tiket, Tanggal Tracking, Status, Status Akun, Bukti)',
            ];

            return;
        }

        $tanggalTracking = $this->parseDate($tanggalTrackingRaw, Carbon::now()->year);
        if (! $tanggalTracking) {
            $this->failedRows[] = [
                'row' => $row->getIndex(),
                'reason' => 'Format tanggal tracking tidak valid',
            ];

            return;
        }

        $tanggal = $this->parseDate($tanggalRaw, $tanggalTracking->year);
        if (! $tanggal) {
            $this->failedRows[] = [
                'row' => $row->getIndex(),
                'reason' => 'Format tanggal tidak valid',
            ];

            return;
        }

        $validatedStatus = $this->validateStatus($status);
        if (! $validatedStatus) {
            $this->failedRows[] = [
                'row' => $row->getIndex(),
                'reason' => 'Status tidak valid. Opsi yang valid: Sedang Diproses, Sedang Diverifikasi, Laporan Diterima, Ditolak',
            ];

            return;
        }

        $validatedAccountStatus = $this->validateAccountStatus($accountStatus);
        if (! $validatedAccountStatus) {
            $this->failedRows[] = [
                'row' => $row->getIndex(),
                'reason' => 'Status akun tidak valid. Opsi yang valid: Masih Aktif, Telah Diblokir',
            ];

            return;
        }

        $platformId = $this->detectPlatform($link);
        if (! $platformId) {
            $this->failedRows[] = [
                'row' => $row->getIndex(),
                'reason' => 'Platform tidak dapat dideteksi dari link. Platform yang didukung: '.$this->getSupportedPlatformsLabel(),
            ];

            return;
        }

        try {
            $maxRetries = 3;
            $retryDelay = 100000;
            $complaintId = null;

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    DB::transaction(function () use (
                        &$complaintId,
                        $namaAkun,
                        $link,
                        $tanggal,
                        $tiket,
                        $platformId,
                    ) {
                        $existingComplaint = $this->complaintsCache[$link] ?? null;

                        if (! $existingComplaint) {
                            $dbComplaint = DB::table('complaints')
                                ->where('account_url', $link)
                                ->first();

                            if ($dbComplaint) {
                                $existingComplaint = [
                                    'id' => $dbComplaint->id,
                                    'username' => $dbComplaint->username,
                                    'platform_id' => $dbComplaint->platform_id,
                                    'ticket' => $dbComplaint->ticket,
                                    'submitted_at' => $dbComplaint->submitted_at,
                                ];

                                $this->complaintsCache[$link] = $existingComplaint;
                            }
                        }

                        if ($existingComplaint) {
                            $complaintId = $existingComplaint['id'];

                            $needsUpdate = (
                                $existingComplaint['username'] !== $namaAkun ||
                                $existingComplaint['platform_id'] !== $platformId ||
                                $existingComplaint['ticket'] !== $tiket ||
                                $existingComplaint['submitted_at'] !== $tanggal->format('Y-m-d H:i:s')
                            );

                            if ($needsUpdate) {
                                DB::table('complaints')
                                    ->where('id', $complaintId)
                                    ->update([
                                        'username' => $namaAkun,
                                        'platform_id' => $platformId,
                                        'description' => 'Dalam grup ini ditemukan indikasi kuat adanya aktivitas prostitusi online...',
                                        'submitted_at' => $tanggal->format('Y-m-d H:i:s'),
                                        'ticket' => $tiket,
                                        'updated_at' => now(),
                                    ]);

                                $this->complaintsCache[$link]['username'] = $namaAkun;
                                $this->complaintsCache[$link]['platform_id'] = $platformId;
                                $this->complaintsCache[$link]['ticket'] = $tiket;
                                $this->complaintsCache[$link]['submitted_at'] = $tanggal->format('Y-m-d H:i:s');
                            }
                        } else {
                            $complaintId = DB::table('complaints')->insertGetId([
                                'user_id' => 1,
                                'username' => $namaAkun,
                                'platform_id' => $platformId,
                                'description' => '-',
                                'account_url' => $link,
                                'submitted_at' => $tanggal->format('Y-m-d H:i:s'),
                                'ticket' => $tiket,
                                'bukti' => null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            $this->complaintsCache[$link] = [
                                'id' => $complaintId,
                                'username' => $namaAkun,
                                'platform_id' => $platformId,
                                'ticket' => $tiket,
                                'submitted_at' => $tanggal->format('Y-m-d H:i:s'),
                            ];
                        }
                    });

                    break;
                } catch (\Illuminate\Database\QueryException $dbEx) {
                    if (
                        $dbEx->getCode() === '40001' ||
                        str_contains($dbEx->getMessage(), 'Lock wait timeout')
                    ) {
                        if ($attempt < $maxRetries) {
                            usleep($retryDelay * $attempt);
                            continue;
                        }
                    }

                    throw $dbEx;
                }
            }

            if ($tangkapanLayar) {
                $bukti = $this->downloadAndSaveScreenshot(
                    $tangkapanLayar,
                    $complaintId,
                    $namaAkun
                );

                if ($bukti) {
                    DB::table('complaints')
                        ->where('id', $complaintId)
                        ->update([
                            'bukti' => $bukti,
                            'updated_at' => now(),
                        ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | INSPECTION LOG DI LUAR TRANSACTION
            |--------------------------------------------------------------------------
            */

            $lastInspection = DB::table('inspections')
                ->where('complaint_id', $complaintId)
                ->orderByDesc('created_at')
                ->first();

            $oldStatus = $lastInspection ? $lastInspection->new_status : '-';

            $this->pendingInspections[] = [
                'complaint_id' => $complaintId,
                'user_id' => 1,
                'old_status' => $oldStatus,
                'new_status' => $validatedStatus,
                'account_status' => $validatedAccountStatus,
                'ticket' => $tiket,
                'inspected_at' => $tanggalTracking->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($this->pendingInspections) >= 50) {
                $this->flushPendingInspections();
            }

            $this->successCount++;

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle specific database errors with user-friendly messages
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            if ($errorCode === '23000' && str_contains($errorMessage, 'Duplicate entry')) {
                // Try to get the existing complaint and continue
                try {
                    $existing = DB::table('complaints')->where('account_url', $link)->first();
                    if ($existing) {
                        $this->complaintsCache[$link] = [
                            'id' => $existing->id,
                            'username' => $existing->username,
                            'platform_id' => $existing->platform_id,
                            'ticket' => $existing->ticket,
                            'submitted_at' => $existing->submitted_at,
                        ];
                        // Mark as success since data already exists
                        $this->successCount++;
                    } else {
                        $this->failedRows[] = [
                            'row' => $row->getIndex(),
                            'reason' => 'Data dengan link yang sama sudah tercatat. Silakan cek daftar laporan.',
                        ];
                    }
                } catch (\Throwable $ex) {
                    $this->failedRows[] = [
                        'row' => $row->getIndex(),
                        'reason' => 'Data ini sudah pernah masuk. Silakan lanjut ke baris berikutnya.',
                    ];
                }
            } elseif (str_contains($errorMessage, 'Lock wait timeout')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Sistem sedang sibuk. Coba ulangi beberapa saat lagi.',
                ];
            } elseif (str_contains($errorMessage, 'Deadlock')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Terjadi kendala saat menyimpan data. Silakan coba lagi.',
                ];
            } elseif (str_contains($errorMessage, 'Connection')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Koneksi terputus. Silakan coba lagi.',
                ];
            } elseif (str_contains($errorMessage, 'foreign key constraint')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Data tidak sesuai. Pastikan platform sudah terdaftar.',
                ];
            } else {
                // Generic database error
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Data belum tersimpan. Silakan coba lagi nanti.',
                ];
            }
        } catch (\Throwable $e) {
            // General error handling with user-friendly message
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'Lock wait timeout')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Sistem sedang sibuk. Coba ulangi beberapa saat lagi.',
                ];
            } elseif (str_contains($errorMessage, 'Deadlock')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Terjadi kendala saat menyimpan data. Silakan coba lagi.',
                ];
            } elseif (str_contains($errorMessage, 'Connection')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Koneksi terputus. Silakan coba lagi.',
                ];
            } elseif (str_contains($errorMessage, 'Duplicate entry')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Data yang sama sudah ada. Silakan cek daftar laporan.',
                ];
            } elseif (str_contains($errorMessage, 'foreign key constraint')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Data tidak sesuai. Pastikan platform sudah terdaftar.',
                ];
            } elseif (
                str_contains($errorMessage, 'Undefined index') ||
                str_contains($errorMessage, 'array key') ||
                str_contains($errorMessage, 'array offset')
            ) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Format kolom tidak sesuai template. Silakan gunakan template terbaru.',
                ];
            } elseif (str_contains($errorMessage, 'file') || str_contains($errorMessage, 'download')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Bukti tidak bisa diambil. Pastikan link atau file tersedia.',
                ];
            } elseif (str_contains($errorMessage, 'memory')) {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'File terlalu besar. Gunakan file dengan ukuran lebih kecil.',
                ];
            } else {
                $this->failedRows[] = [
                    'row' => $row->getIndex(),
                    'reason' => 'Data tidak bisa diproses. Pastikan format tanggal, status, dan link sudah benar.',
                ];
            }
        }
    }

    /**
     * Parse date value from Excel or string format to Carbon instance.
     *
     * Handles both numeric Excel date format and string date format.
     *
     * @param mixed $value The date value to parse
     * @return Carbon|null Parsed date or null if invalid
     */
    protected function parseDate($value, ?int $fallbackYear = null): ?Carbon
    {
        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            }

            if (is_string($value) && trim($value) !== '') {
                $normalized = $this->normalizeIndonesianDateString($value);
                if ($fallbackYear && ! $this->hasYearInDateString($normalized)) {
                    $normalized = trim($normalized).' '.$fallbackYear;
                }

                $formats = [
                    'd F Y',
                    'd M Y',
                    'd F',
                    'd M',
                    'd/m/Y',
                    'd-m-Y',
                    'd/m',
                    'd-m',
                    'Y-m-d',
                    'Y/m/d',
                ];

                foreach ($formats as $format) {
                    $date = Carbon::createFromFormat($format, $normalized);
                    if ($date !== false) {
                        return $date;
                    }
                }

                return Carbon::parse($normalized);
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    /**
     * Check if a date string already contains a 4-digit year.
     */
    protected function hasYearInDateString(string $value): bool
    {
        return (bool) preg_match('/\b\d{4}\b/', $value);
    }

    /**
     * Normalize Indonesian month names to English for reliable parsing.
     */
    protected function normalizeIndonesianDateString(string $value): string
    {
        $value = trim($value);

        $replacements = [
            'januari' => 'January',
            'februari' => 'February',
            'feb' => 'Feb',
            'maret' => 'March',
            'apr' => 'Apr',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'agt' => 'Aug',
            'september' => 'September',
            'sept' => 'Sep',
            'oktober' => 'October',
            'okt' => 'Oct',
            'november' => 'November',
            'desember' => 'December',
        ];

        $lower = strtolower($value);
        foreach ($replacements as $id => $en) {
            if (str_contains($lower, $id)) {
                return str_ireplace($id, $en, $value);
            }
        }

        return $value;
    }

    /**
     * Validate and normalize report status from various input formats.
     *
     * Accepts variations of: sedang-diproses, sedang-diverifikasi, laporan-diterima, ditolak
     *
     * @param string $status Status value from Excel (case-insensitive)
     * @return string|null Normalized status or null if invalid
     */
    protected function validateStatus(string $status): ?string
    {
        $validStatuses = [
            'sedang-diproses',
            'sedang-diverifikasi',
            'laporan-diterima',
            'ditolak',
        ];

        $normalizedInput = strtolower(trim($status));

        $statusMap = [
            'sedang diproses' => 'sedang-diproses',
            'sedang-diproses' => 'sedang-diproses',
            'sedangdiproses' => 'sedang-diproses',
            'proses' => 'sedang-diproses',
            'sedang diverifikasi' => 'sedang-diverifikasi',
            'sedang-diverifikasi' => 'sedang-diverifikasi',
            'sedangdiverifikasi' => 'sedang-diverifikasi',
            'verifikasi' => 'sedang-diverifikasi',
            'laporan diterima' => 'laporan-diterima',
            'laporan-diterima' => 'laporan-diterima',
            'laporanditerima' => 'laporan-diterima',
            'diterima' => 'laporan-diterima',
            'ditolak' => 'ditolak',
            'tolak' => 'ditolak',
        ];

        if (isset($statusMap[$normalizedInput])) {
            return $statusMap[$normalizedInput];
        }

        foreach ($validStatuses as $validStatus) {
            if (strtolower($validStatus) === $normalizedInput) {
                return $validStatus;
            }
        }

        return null;
    }

    /**
     * Validate and normalize account status from various input formats.
     *
     * Accepts variations of: masih aktif, telah diblokir
     */
    protected function validateAccountStatus(string $status): ?string
    {
        $normalizedInput = strtolower(trim($status));

        $statusMap = [
            'masih aktif' => 'Masih Aktif',
            'aktif' => 'Masih Aktif',
            'telah diblokir' => 'Telah Diblokir',
            'diblokir' => 'Telah Diblokir',
            'blokir' => 'Telah Diblokir',
        ];

        return $statusMap[$normalizedInput] ?? null;
    }

    /**
     * Detect social media platform from account URL.
     *
     * Returns platform ID: 1=X/Twitter, 2=Instagram, 3=Facebook, 4=TikTok
     *
     * @param string $url The account/post URL
     * @return int|null Platform ID or null if not recognized
     */
    protected function detectPlatform(string $url): ?int
    {
        $url = strtolower($url);
        $host = $this->normalizeHost($url);

        foreach ($this->platformsCache as $platform) {
            if ($platform['host'] && $host && str_ends_with($host, $platform['host'])) {
                return $platform['id'];
            }

            if ($platform['url'] !== '' && str_contains($url, $platform['url'])) {
                return $platform['id'];
            }
        }

        return null;
    }

    /**
     * Flush pending inspections to database in batch.
     */
    protected function flushPendingInspections(): void
    {
        if (empty($this->pendingInspections)) {
            return;
        }

        DB::table('inspections')->insert($this->pendingInspections);
        $this->pendingInspections = [];
    }

    /**
     * Normalize URL or host string into a lowercase host without scheme or www.
     */
    protected function normalizeHost(string $value): ?string
    {
        $value = strtolower(trim($value));
        if ($value === '') {
            return null;
        }

        if (! preg_match('#^https?://#', $value)) {
            $value = 'http://'.$value;
        }

        $host = parse_url($value, PHP_URL_HOST);
        if (! $host) {
            return null;
        }

        return preg_replace('/^www\./', '', $host);
    }

    /**
     * Get a comma-separated list of supported platforms from the database.
     */
    protected function getSupportedPlatformsLabel(): string
    {
        $names = array_map(function ($platform) {
            return $platform['name'];
        }, $this->platformsCache ?? []);

        return $names ? implode(', ', $names) : '-';
    }

    /**
     * Convert Google Drive sharing link to direct download format.
     *
     * Handles multiple Google Drive URL formats and converts to direct download link.
     *
     * @param string $url Google Drive URL or direct link
     * @return string|null Direct download URL or null if invalid
     */
    protected function convertDriveLink(string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $patterns = [
            '/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/',
            '/docs\.google\.com\/.*\/d\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $fileId = $matches[1];
                return "https://drive.google.com/uc?id={$fileId}";
            }
        }

        return $url;
    }

    /**
     * Download or copy evidence file and save to local storage.
     *
     * Routes to Google Drive download or local file copy based on source.
     *
     * @param string $url Google Drive URL or local filename
     * @param int $complaintId ID of the complaint record
     * @param string $username Username for naming the file
     * @return string|null Saved filename or null if failed
     */
    protected function downloadAndSaveScreenshot(string $url, int $complaintId, string $username): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (str_contains($url, 'drive.google.com') || str_contains($url, 'docs.google.com')) {
            return $this->downloadFromDrive($url, $username);
        }

        return $this->copyLocalFile($url, $username);
    }

    /**
     * Download evidence file from Google Drive and save to storage.
     *
     * File is saved to storage/app/submissions/ with filename based on username.
     * Falls back to original URL on failure.
     *
     * @param string $url Google Drive URL
     * @param int $complaintId ID of the complaint (for context)
     * @param string $username Account name for filename generation
     * @return string|null Saved filename or original URL on fallback
     */
    protected function downloadFromDrive(string $url, string $username): ?string
    {
        try {
            $downloadUrl = $this->convertDriveLink($url);

            if (! $downloadUrl) {
                return null;
            }

            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                ],
            ]);

            $fileContent = @file_get_contents($downloadUrl, false, $context);

            if ($fileContent === false) {
                throw new \Exception('Gagal mengunduh file dari Google Drive');
            }

            $extension = $this->getExtensionFromUrl($downloadUrl);

            $usernameSlug = Str::slug($username, '-');
            $imagename = $usernameSlug.'.'.$extension;
            $path = 'submissions/'.$imagename;

            Storage::disk('local')->put($path, $fileContent);

            return $imagename;

        } catch (\Throwable $e) {
            return $this->convertDriveLink($url);
        }
    }

    /**
     * Copy local evidence file from import folder to submission storage.
     *
     * Reads file from storage/imports/bukti/ and saves to storage/app/submissions/.
     *
     * @param string $filename Name of file in import folder
     * @param int $complaintId ID of the complaint (for context)
     * @param string $username Account name for filename generation
     * @return string|null Saved filename or null if failed
     */
    protected function copyLocalFile(string $filename, string $username): ?string
    {
        try {
            $sourceDir = storage_path('imports/bukti');

            if (! is_dir($sourceDir)) {
                return null;
            }

            $sourcePath = $sourceDir.'/'.$filename;

            if (! file_exists($sourcePath)) {
                return null;
            }

            $fileContent = file_get_contents($sourcePath);

            if ($fileContent === false) {
                return null;
            }

            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            $usernameSlug = Str::slug($username, '-');
            $imagename = $usernameSlug.'.'.$extension;
            $path = 'submissions/'.$imagename;

            Storage::disk('local')->put($path, $fileContent);

            return $imagename;

        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Extract file extension from URL or default to jpg.
     *
     * Parses URL path to get extension, validates length, defaults to jpg if not found.
     *
     * @param string $url The file URL
     * @return string File extension without dot
     */
    protected function getExtensionFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if ($ext && strlen($ext) <= 4) {
                return strtolower($ext);
            }
        }

        return 'jpg';
    }

    /**
     * Get summary of import results.
     *
     * Flushes any remaining pending inspections before returning summary.
     *
     * @return array Array with keys: total_rows, success_count, failed_rows
     */
    public function getSummary(): array
    {
        // Flush any remaining pending inspections
        $this->flushPendingInspections();

        return [
            'total_rows' => $this->totalRows,
            'success_count' => $this->successCount,
            'failed_rows' => $this->failedRows,
        ];
    }
}
