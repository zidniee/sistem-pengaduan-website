<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseApiController;
use App\Models\ImportLog;
use Illuminate\Http\Request;

class ImportStatusApiController extends BaseApiController
{
    public function show(Request $request, int $id)
    {
        $importLog = ImportLog::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return $this->success([
            'id' => $importLog->id,
            'status' => $importLog->status,
            'total_rows' => $importLog->total_rows,
            'success_count' => $importLog->success_count,
            'failed_count' => $importLog->failed_count,
            'failed_rows' => $importLog->failed_rows,
            'error_message' => $importLog->error_message,
            'started_at' => $importLog->started_at,
            'completed_at' => $importLog->completed_at,
            'progress_percentage' => $importLog->getProgressPercentage(),
        ], 'Status import berhasil diambil');
    }
}
