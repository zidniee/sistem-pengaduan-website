<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaints;

class ReportController extends Controller
{
    /**
     * Track complaint status by ticket code or report ID.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function track(Request $request)
    {
        $request->validate([
            'kode_laporan' => 'required|string|min:1',
        ], [
            'kode_laporan.required' => 'Kode laporan harus diisi.',
        ]);

        $kode = trim($request->input('kode_laporan'));

        // Search for complaint by ticket code (case-insensitive) with latest inspections
        $laporan = Complaints::with(['platform', 'LatestInspection'])
            ->where('ticket', $kode)
            ->first();

        // Redirect with error if complaint not found
        if (!$laporan) {
            return redirect()
                ->back()
                ->with('track_error', 'Kode laporan tidak ditemukan. Silakan periksa kembali kode yang Anda masukkan.')
                ->withInput();
        }

        // Display tracking result view with complaint details
        return view('form.result-report', compact('laporan'));
    }
}
