<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Complaints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ComplaintApiController extends BaseApiController
{
    public function store(Request $request)
    {
        $rawUrl = trim((string) $request->input('url'));
        if ($rawUrl !== '' && !preg_match('~^https?://~i', $rawUrl)) {
            $rawUrl = 'https://' . $rawUrl;
        }

        $request->merge(['url' => $rawUrl]);

        $validated = $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'platform' => 'required|exists:platforms,id',
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date|before_or_equal:today',
            'url' => 'required|url|max:500|unique:complaints,account_url',
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'bukti.required' => 'Screenshot wajib diunggah',
            'bukti.image' => 'File harus berupa gambar',
            'bukti.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'bukti.max' => 'Ukuran gambar maksimal 5MB',
            'platform.required' => 'Platform wajib dipilih',
            'platform.exists' => 'Platform tidak valid',
            'nama.required' => 'Nama akun wajib diisi',
            'tanggal.required' => 'Tanggal temuan wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'url.required' => 'URL konten wajib diisi',
            'url.url' => 'Format URL tidak valid',
            'url.unique' => 'URL konten sudah pernah dilaporkan',
            'alasan.required' => 'Alasan pelaporan wajib diisi',
            'alasan.min' => 'Alasan minimal 10 karakter',
        ]);

        $extension = $request->file('bukti')->getClientOriginalExtension();
        $usernameSlug = Str::slug($validated['nama'], '-');
        $imageName = $usernameSlug . '-' . now()->format('YmdHis') . '.' . $extension;

        $complaint = new Complaints();
        $complaint->user_id = Auth::id();
        $complaint->platform_id = $validated['platform'];
        $complaint->username = $validated['nama'];
        $complaint->submitted_at = $validated['tanggal'];
        $complaint->account_url = $validated['url'];
        $complaint->description = $validated['alasan'];
        $complaint->bukti = $imageName;

        // Generate tracking ticket for API flow if not provided elsewhere.
        $complaint->ticket = 'TKT-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        $complaint->save();
        $request->file('bukti')->storeAs('submissions', $imageName, 'local');

        return $this->success([
            'id' => $complaint->id,
            'ticket' => $complaint->ticket,
            'submitted_at' => $complaint->submitted_at?->toDateString(),
            'status' => 'sedang-diproses',
        ], 'Laporan berhasil dikirim', 201);
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'kode_laporan' => 'required|string|min:1',
        ], [
            'kode_laporan.required' => 'Kode laporan harus diisi.',
        ]);

        $kode = trim($validated['kode_laporan']);

        $query = Complaints::with(['platform', 'latestInspection'])
            ->where('ticket', $kode);

        if (ctype_digit($kode)) {
            $query->orWhere('id', (int) $kode);
        }

        $laporan = $query->first();

        if (!$laporan) {
            return $this->error('Kode laporan tidak ditemukan', null, 404);
        }

        return $this->success([
            'id' => $laporan->id,
            'ticket' => $laporan->ticket,
            'submitted_at' => $laporan->submitted_at?->toDateString(),
            'platform' => [
                'id' => $laporan->platform?->id,
                'name' => $laporan->platform?->name,
            ],
            'username' => $laporan->username,
            'account_url' => $laporan->account_url,
            'description' => $laporan->description,
            'status' => [
                'code' => $laporan->latestInspection?->new_status,
                'label' => Complaints::getStatusConfig($laporan->latestInspection?->new_status)['label'] ?? 'Belum ada inspeksi',
                'account_status' => $laporan->latestInspection?->account_status,
                'inspected_at' => $laporan->latestInspection?->inspected_at,
            ],
        ], 'Data laporan ditemukan');
    }
}
