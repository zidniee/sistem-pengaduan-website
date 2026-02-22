<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'status',
        'total_rows',
        'success_count',
        'failed_count',
        'failed_rows',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'failed_rows' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted(array $summary)
    {
        $this->update([
            'status' => 'completed',
            'total_rows' => $summary['total_rows'],
            'success_count' => $summary['success_count'],
            'failed_count' => count($summary['failed_rows']),
            'failed_rows' => $summary['failed_rows'],
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed(string $error)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
            'completed_at' => now(),
        ]);
    }

    public function getProgressPercentage(): float
    {
        if (!$this->total_rows || $this->total_rows == 0) {
            return 0;
        }

        return round(($this->success_count + $this->failed_count) / $this->total_rows * 100, 2);
    }
}
