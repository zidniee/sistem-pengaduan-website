<?php

use App\Http\Controllers\Api\V1\Admin\ImportStatusApiController;
use App\Http\Controllers\Api\V1\ComplaintApiController;
use App\Http\Controllers\Api\V1\PlatformApiController;
use App\Http\Controllers\Api\V1\SessionApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('meta/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'API is healthy',
            'data' => [
                'service' => config('app.name'),
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    });

    Route::get('platforms', [PlatformApiController::class, 'index']);

    Route::post('complaints', [ComplaintApiController::class, 'store'])
        ->middleware('throttle:5,1');

    Route::post('complaints/track', [ComplaintApiController::class, 'track'])
        ->middleware('throttle:20,1');

    Route::post('auth/login', [SessionApiController::class, 'login'])
        ->middleware('throttle:10,1');

    Route::middleware('api.token')->group(function () {
        Route::get('auth/me', [SessionApiController::class, 'me']);
        Route::post('auth/logout', [SessionApiController::class, 'logout']);

        Route::middleware('operator')->group(function () {
            Route::get('admin/imports/{id}/status', [ImportStatusApiController::class, 'show']);
        });
    });
});
