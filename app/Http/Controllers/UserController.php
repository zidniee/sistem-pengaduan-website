<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaints;
use App\Models\Platforms;
use App\Models\Inspections;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display the complaint submission form with available platforms.
     *
     * @return \Illuminate\View\View
     */
    public function submitReportForm() {
        $complaints = Complaints::all();
        // Fetch all available platforms for dropdown selection
        $platforms = Platforms::all();

        return view('form.reports')->with(compact('complaints', 'platforms'));
    }

    /**
     * Submit a new complaint with evidence file upload and validation.
     *
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitComplaints(Request $req) {
        // Normalize URL by adding https:// prefix if missing
        $rawUrl = trim((string) $req->input('url'));
        if ($rawUrl !== '' && !preg_match('~^https?://~i', $rawUrl)) {
            $rawUrl = 'https://' . $rawUrl;
        }

        $req->merge(['url' => $rawUrl]);

        // Validate complaint submission with custom error messages
        $validated = $req->validate([
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
            'nama.required' => 'Nama akun wajib diisi',
            'tanggal.required' => 'Tanggal temuan wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'url.required' => 'URL konten wajib diisi',
            'url.url' => 'Format URL tidak valid',
            'url.unique' => 'URL konten sudah pernah dilaporkan',
            'alasan.required' => 'Alasan pelaporan wajib diisi',
            'alasan.min' => 'Alasan minimal 10 karakter',
        ]);

        try {
            $complaints = new Complaints();
            $complaints->user_id = Auth::id();
            $complaints->platform_id = $validated['platform'];
            $complaints->username = $validated['nama'];
            $complaints->submitted_at = $validated['tanggal'];
            $complaints->account_url = $validated['url'];
            $complaints->description = $validated['alasan'];

            // Generate filename using the same convention as LaporanImport
            $extension = $req->file('bukti')->getClientOriginalExtension();
            $usernameSlug = Str::slug($validated['nama'], '-');
            $imagename = $usernameSlug.'.'.$extension;
            $complaints->bukti = $imagename;

            // Save complaint to database
            $complaints->save();
            
            // Store uploaded evidence file
            $req->file('bukti')->storeAs('submissions', $imagename, 'local');

            session()->flash('success', 'Laporan berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($req->all())
                ->with('error', 'Gagal mengirim laporan. Silakan coba lagi. (' . $e->getMessage() . ')');
        }

        return redirect()->back();
    }

    /**
     * Display user dashboard with complaint statistics and recent submissions.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard() {
        $user = Auth::user();
        
        // Fetch today's complaints for the authenticated user (limit 20)
        $complaints = Complaints::with('platform')
            ->whereDate('created_at', Carbon::today())
            ->where('user_id', $user->id)
            ->take(20)
            ->get();
        
        // Count total complaints by user
        $totalComplaints = Complaints::with('platform')->where('user_id', $user->id)->count();
        
        // Count complaints currently being processed
        $processingComplaints = Complaints::whereHas('latestInspection', function ($query) use ($user) {
            $query->where('new_status', 'sedang-diproses');
        })
        ->where('user_id', $user->id)
        ->count();
        
        // Count completed complaints (accepted reports)
        $completedComplaints = Complaints::whereHas('latestInspection', function ($query) use ($user) {
            $query->where('new_status', 'laporan-diterima');
        })
        ->where('user_id', $user->id)
        ->count();

        return view('User.dashboard', compact('user', 'totalComplaints', 'processingComplaints', 'completedComplaints', 'complaints'));
    }

    /**
     * Display paginated complaint history for authenticated user with search and status filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function history(Request $request) {
        $search = trim($request->query('search'));
        $status = $request->query('status');
        $user = Auth::user();
        
        // Base query: fetch user's complaints with relations
        $query = Complaints::with(['platform', 'latestInspection'])->where('user_id', $user->id);
        
        // Apply search filter across multiple fields
        if($search) {
            $query->where(function($query) use ($search) {
                $query->where('ticket', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('account_url', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply status filter based on latest inspection
        if($status) {
            $query->whereHas('latestInspection', function ($query) use ($status) {
                $query->where('new_status', $status);
            });
        }
        
        // Paginate results and preserve query string
        $complaints = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        return view('User.history', compact('user', 'complaints', 'status', 'search'));
    }

}


