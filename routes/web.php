<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ReportController;

Route::get('/', function() {
    return view('homepage');
})->name('homepage');

Route::get('/laporan', [UserController::class, 'submitReportForm'])->name('reports')->middleware('throttle:60,1');
Route::get('lapor', [UserController::class, 'submitReportForm']);
Route::post('lapor', [UserController::class, 'submitComplaints'])->name('submitComplaints')->middleware('throttle:5,1'); // 5 submissions per minute

// Lacak laporan
Route::get('/lacak-laporan', fn() => redirect()->route('homepage')
    ->with('track_error', 'Kode laporan tidak ditemukan. Silakan periksa kembali kode yang Anda masukkan.')
    ->withInput());
Route::post('/lacak-laporan', [ReportController::class, 'track'])
    ->name('track-laporan')
    ->middleware('throttle:20,1'); // 20 tracking requests per minute

Route::middleware('auth')->group(function() {
    Route::get('user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('user/history', [UserController::class, 'history'])->name('user.history')->middleware('throttle:30,1'); // 30 requests per minute
});

Route::middleware('operator')->group(function() {
    Route::get('admin/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard');
    Route::post('admin/dashboard', [OperatorController::class, 'addComplaint'])->name('operatorSubmitComplaints');
    Route::get('admin/daftar-laporan/bukti/{id}', [OperatorController::class, 'showEvidence'])->name('admin.bukti');
    Route::get('admin/data_laporan-perhari', [OperatorController::class, 'fetchDailyReport'])->name('lapor-perhari');
    #daftar laporan
    Route::get('admin/daftar-laporan', [OperatorController::class, 'complaintsList'])->name('complaint-list');
    Route::get('admin/daftar-laporan/aduan/{id}', [OperatorController::class, 'complaintDetail'])->name('complaint.detail');
    Route::put('admin/daftar-laporan/aduan/{id}', [OperatorController::class, 'updateComplaint'])->name('complaint.update')->middleware('throttle:30,1'); // 30 updates per minute
    Route::get('admin/daftar-laporan/download-bukti/{id}', [OperatorController::class, 'downloadEvidence'])->name('complaint.download-evidence')->middleware('throttle:30,1'); // 30 downloads per minute
    // Input Platform
    Route::get('admin/platforms', [OperatorController::class, 'showPlatforms'])->name('platforms.list');
    Route::post('admin/platforms', [OperatorController::class, 'addPlatform'])->name('platforms.add')->middleware('throttle:10,1'); // 10 additions per minute
    Route::delete('admin/platforms/{id}', [OperatorController::class, 'deletePlatform'])->name('platforms.delete')->middleware('throttle:10,1'); // 10 deletions per minute
    //Generate PDF Report
    Route::get('admin/daftar-laporan/audit/pdf', [OperatorController::class, 'generatePDFReport'])->name('laporan.audit.pdf');// ->middleware('throttle:5,1440'); // 5 PDFs per day
    // Import Excel Laporan
    Route::get('admin/daftar-laporan/import', [OperatorController::class, 'showImportForm'])->name('laporan.import.form');
    Route::post('admin/daftar-laporan/import', [OperatorController::class, 'importLaporan'])->name('laporan.import')->middleware('throttle:10,1'); // 10 imports per minute
    Route::get('admin/daftar-laporan/import/history', [OperatorController::class, 'importHistory'])->name('laporan.import.history');
    Route::get('admin/daftar-laporan/import/template', [OperatorController::class, 'downloadImportTemplate'])->name('laporan.template.download');
    // Get Import Status (AJAX)
    Route::get('operator/import-status/{id}', [OperatorController::class, 'getImportStatus'])->name('import.status');
});


require __DIR__.'/auth.php';