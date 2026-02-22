<?php

namespace App\Jobs;

use App\Imports\LaporanImport;
use App\Models\ImportLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportLaporanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 7200; // 2 hours

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;

    protected $importLogId;
    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(int $importLogId, string $filePath)
    {
        $this->importLogId = $importLogId;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $importLog = ImportLog::find($this->importLogId);

        if (!$importLog) {
            return;
        }

        $disk = Storage::disk('local');
        if (! $disk->exists($this->filePath)) {
            $importLog->markAsFailed('File tidak ditemukan. Silakan unggah ulang.');
            return;
        }

        try {
            // Mark as processing
            $importLog->markAsProcessing();

            // Perform the import
            $import = new LaporanImport();
            $fullPath = $disk->path($this->filePath);
            Excel::import($import, $fullPath);

            // Get summary and update log
            $summary = $import->getSummary();
            $importLog->markAsCompleted($summary);

            // Clean up after successful import
            if ($disk->exists($this->filePath)) {
                $disk->delete($this->filePath);
            }

        } catch (\Throwable $e) {
            // Mark as failed
            $importLog->markAsFailed($this->formatErrorMessage($e));
            
            // Re-throw to let Laravel handle job failure
            throw $e;
        }
    }

    /**
     * Ensure only one import runs at a time.
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('import-laporan'))
                ->expireAfter($this->timeout)
                ->releaseAfter(60),
        ];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $importLog = ImportLog::find($this->importLogId);

        if ($importLog) {
            $importLog->markAsFailed($this->formatErrorMessage($exception));
        }

        $disk = Storage::disk('local');
        if ($disk->exists($this->filePath)) {
            $disk->delete($this->filePath);
        }
    }

    /**
     * Build a user-friendly error message for import failures.
     */
    protected function formatErrorMessage(\Throwable $exception): string
    {
        $message = $exception->getMessage();

        if (str_contains($message, 'does not exist')) {
            return 'File tidak ditemukan. Silakan unggah ulang.';
        }

        if (str_contains($message, 'Lock wait timeout')) {
            return 'Sistem sedang sibuk. Silakan coba lagi.';
        }

        if (str_contains($message, 'Duplicate entry')) {
            return 'Data yang sama sudah ada. Silakan cek daftar laporan.';
        }

        return 'Terjadi kendala saat memproses file. Silakan coba lagi.';
    }
}
