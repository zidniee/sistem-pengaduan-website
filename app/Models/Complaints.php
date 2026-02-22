<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Support\Facades\Schema;

class Complaints extends Model
{
    use HasFactory;


    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'date',
    ];

    public static function getAccountStatusConfig($account_status = null) {
        $config = [
            'Masih Aktif' => [
                'bg' => 'bg-red-800',
                'text' => 'text-white',
                'label' => 'Masih Aktif',
            ],
            'Telah Diblokir' => [
                'bg' => 'bg-blue-100', 
                'text' => 'text-black',
                'label' => 'Telah Diblokir',
            ],
        ];

            if ($account_status) {
            return $config[$account_status] ?? [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-800',
                'label' => ucfirst($account_status)
            ];
        }

        return $config;
    }

    // Status configuration
    public static function getStatusConfig($status = null)
    {
        $config = [
            'sedang-diproses' => [
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'label' => 'Sedang Diproses'
            ],
            'sedang-diverifikasi' => [
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-800',
                'label' => 'Sedang Diverifikasi'
            ],
            'laporan-diterima' => [
                'bg' => 'bg-green-100',
                'text' => 'text-green-800',
                'label' => 'Laporan Diterima'
            ],
            'ditolak' => [
                'bg' => 'bg-red-100',
                'text' => 'text-red-800',
                'label' => 'Ditolak'
            ],
        ];

        if ($status) {
            return $config[$status] ?? [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-800',
                'label' => ucfirst($status)
            ];
        }

        return $config;
    }

    // Get status badge HTML classes
    public function getStatusBadge()
    {
        return self::getStatusConfig($this->status);
    }

    // Relate complaints with platforms
    public function platform() {
        return $this->belongsTo(Platforms::class, 'platform_id');
    }

    // Relate complaints with users
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relate complaints with inspections
    public function inspections() {
        return $this->hasMany(Inspections::class, 'complaint_id')->orderBy('inspected_at', 'asc');
    }

    // Latest inspection (log terakhir)
    public function latestInspection() {
        return $this->hasOne(Inspections::class, 'complaint_id')->latestOfMany();  //('inspected_at');
    }

    public function scopeFilterComplaints($query, $search = null, $status = null, $semester = null, $month = null, $accountStatus = null)
    {
        return $query
            ->when($search, function ($q) use ($search) {
                return $q->where(function ($query) use ($search) {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhere('ticket', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhereHas('platform', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($q) use ($status) {
                return $q->whereHas('latestInspection', function ($query) use ($status) {
                    $query->where('new_status', $status);
                });
            })
            ->when($accountStatus, function ($q) use ($accountStatus) {
                return $q->whereHas('latestInspection', function ($query) use ($accountStatus) {
                    $query->where('account_status', $accountStatus);
                });
            })
            ->when($month, function ($q) use ($month) {
                try {
                    $monthDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                    $startDate = $monthDate->copy()->startOfDay();
                    $endDate = $monthDate->copy()->endOfMonth()->endOfDay();

                    return $q->whereBetween('submitted_at', [$startDate, $endDate]);
                } catch (\Exception $e) {
                    return $q;
                }
            })
            ->when($semester && ! $month, function ($q) use ($semester) {
                $parts = explode('-', $semester);

                if (count($parts) !== 2) {
                    return $q;
                }

                [$year, $sem] = $parts;

                if (! in_array($sem, ['1', '2'], true)) {
                    return $q;
                }

                if ($sem == '1') {
                    $startDate = $year.'-01-01';
                    $endDate = $year.'-06-30';
                } else {
                    $startDate = $year.'-07-01';
                    $endDate = $year.'-12-31';
                }

                return $q->whereBetween('submitted_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
            });
    }
}
