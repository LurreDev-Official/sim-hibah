<?php

namespace App\Http\Controllers;

use App\Models\Luaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Usulan;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanAkhir;
use App\Models\LaporanKemajuan;
use App\Exports\LuaranExport;

use Maatwebsite\Excel\Facades\Excel;


class LuaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $luarans = Luaran::all(); // Fetch all Luaran records
        return view('luaran.index', compact('luarans')); // Return the view with the data
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
{
    // Mengambil data Usulan berdasarkan ID
    $usulan = Usulan::findOrFail($id); // Lebih efisien daripada where()->first()

    // Mengambil data Luaran terkait usulan
    $luarans = Luaran::where('usulan_id', $id)->pluck('type')->toArray(); // Mengambil jenis luaran yang sudah ada

    // Mengambil laporan akhir terkait usulan
    $laporanakhir = LaporanAkhir::where('usulan_id', $id)->first();

    // Mengambil dokumen laporan akhir jika ada
    $dokumenLaporanAkhir = $laporanakhir ? $laporanakhir->dokumen_laporan_akhir : null;

    // Jenis-jenis luaran yang wajib
    $jenisLuarans = [
        'Laporan akhir',
        'Artikel ilmiah',
        'Prosiding'
    ];

    // Filter jenisLuarans yang belum ada di database
    $missingLuarans = array_diff($jenisLuarans, $luarans);

    // Cek dan insert hanya jenis luaran yang belum ada
    foreach ($missingLuarans as $jenisLuaran) {
        // Cek jika luaran dengan jenis ini dan usulan_id sudah ada
        $existingLuaran = Luaran::where('usulan_id', $usulan->id)
                                ->where('type', $jenisLuaran . $usulan->jenis_skema) // Pastikan type unik
                                ->first();

        // Jika luaran dengan jenis ini belum ada, buat yang baru
        if (!$existingLuaran) {
            $url = '';
            $judul = '';
            $status = '';

            // Jika jenis luaran adalah 'Laporan akhir', tentukan URL otomatis
            if ($jenisLuaran == 'Laporan akhir') {
                $judul = $usulan->judul_usulan;
                $status = 'Belum terpenuhi';
                $url = $dokumenLaporanAkhir ? url('storage/' . $dokumenLaporanAkhir) : ''; // Jika ada dokumen, ambil URL-nya
            }

            // Jika jenis luaran adalah 'Artikel ilmiah di jurnal terakreditasi minimal'
            if ($jenisLuaran == 'Artikel ilmiah') {
                if ($usulan->jenis_skema == 'penelitian') {
                    $judul = 'Artikel ilmiah di jurnal terakreditasi minimal sinta 4';
                } else {
                    $judul = 'Artikel ilmiah di jurnal terakreditasi minimal sinta 5';
                }
                $status = 'Belum terpenuhi';
                $url = '';
            }

            // Jika jenis luaran adalah 'Artikel ilmiah di prosiding SAINSTEKNOPAK'
            if ($jenisLuaran == 'Prosiding') {
                $judul = 'Artikel ilmiah di prosiding SAINSTEKNOPA Sinta 5';
                $status = 'Belum terpenuhi';
                $url = '';
            }

            // Membuat Luaran baru dengan URL yang sesuai
            Luaran::create([
                'laporankemajuan_id' => 0, // Nilai default
                'laporanakhir_id' => $laporanakhir ? $laporanakhir->id : 0, // Ambil ID laporan akhir jika ada
                'usulan_id' => $usulan->id, // Menghubungkan dengan usulan
                'jenis_luaran' => 'wajib', // Nilai default
                'judul' => $judul, // Judul yang telah ditentukan
                'type' => $jenisLuaran, // Jenis luaran yang hilang
                'url' => $url, // URL yang telah diisi
                'status' => $status, // Status default
                'file_loa' => 0, // Nilai default
                'jenis_skema' => $usulan->jenis_skema, // Jenis skema usulan
            ]);
        }
    }

    // Mengambil semua luaran setelah penambahan
    $luarans = Luaran::where('usulan_id', $id)->get();

    // Kirim data ke view
    $jenis = $usulan->jenis_skema;
    return view('luaran.create', compact('luarans', 'usulan', 'jenis'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'usulan_id' => 'required|exists:usulans,id', // Ensure usulan_id exists in the usulans table
        'judul' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'url' => 'required|url',
        'file_loa' => 'nullable|file|mimes:pdf,doc,docx|max:10048', // Make file_loa optional
    ]);

    try {
        // Initialize filePath to null
        $filePath = null;

        // Handle file upload if a file is provided
        if ($request->hasFile('file_loa')) {
            $file = $request->file('file_loa');
            
            // Generate a unique filename
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            // Store the file in the 'luaran' directory within the 'public' disk
            $filePath = $file->storeAs('luaran', $filename, 'public');
        }

        // Retrieve related IDs
        $laporankemajuan_id = LaporanKemajuan::where('usulan_id', $request->usulan_id)->first()->id;
        $laporanakhir_id = LaporanAkhir::where('usulan_id', $request->usulan_id)->first()->id;
        // Create a new Luaran record
        Luaran::create([
            'usulan_id' => $request->usulan_id,
            'laporankemajuan_id' => $laporankemajuan_id,
            'laporanakhir_id' => $laporanakhir_id,
            'jenis_luaran' => 'tambahan',  // Default value
            'judul' => $request->judul,
            'type' => $request->type,
            'url' => $request->url,
            'status' => 'Terpenuhi',  // Default value
            'file_loa' => $filePath, // This will be null if no file was uploaded
        ]);

        // Redirect with success messagew
        return redirect()->back()->with('success', 'Berhasil di tambahkan');

    } catch (\Exception $e) {
        // Redirect back with error message
        return redirect()->back()->with('error', 'An error occurred while creating the Luaran: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        
        $user = auth()->user(); // Ambil data user yang sedang login
        if ($user->hasRole('Kepala LPPM')) {
            $usulans = Usulan::where('jenis_skema', $jenis)->where('status', 'approved')->get();
            return view('luaran.index', compact('usulans', 'jenis'));

        }
        else{
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                // Ambil semua usulan_id yang terkait dengan dosen dari tabel AnggotaDosen
                $usulanIds = AnggotaDosen::where('dosen_id', $dosen->id)->pluck('usulan_id');
                // Ambil semua usulan berdasarkan usulan_id yang ditemukan di AnggotaDosen
                $usulans = Usulan::whereIn('id', $usulanIds)
                                 ->where('jenis_skema', $jenis)
                                 ->where('status', 'approved')->get();
                // dd($usulans);
                                 return view('luaran.index', compact('usulans', 'jenis'));
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Luaran $luaran)
    {
      
        $luaran = Luaran::findOrFail($luaran->id); 
        $usulan = Usulan::findOrFail($luaran->usulan_id);
        // Check if the user has the 'Dosen' role
        if ($user->hasRole('Dosen')) {
            // Filter Usulan based on ketua_dosen_id and optionally the 'jenis' if provided
            $usulans = Usulan::where('ketua_dosen_id', $usulan->ketua_dosen_id)
            ->when($jenis, function($query, $jenis) {
                return $query->where('jenis_skema', $jenis);  // Filtering by jenis
            })
            ->where('status', 'approved') // Adding the status filter after the initial condition
            ->get();
        
            return view('luaran.create', compact('usulans', 'jenis'));
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membuat laporan.');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Luaran $luaran)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'url' => 'required',
        'file_loa' => 'nullable|file|mimes:pdf,doc,docx|max:10048', // Optional file upload
    ]);

    // Update the Luaran record
    $luaran->judul = $request->judul;
    $luaran->type = $request->type;
    $luaran->url = $request->url;

    // Handle file upload if a new file is provided
    if ($request->hasFile('file_loa')) {
        // Delete the old file if it exists
        if ($luaran->file_loa) {
            Storage::disk('public')->delete($luaran->file_loa);
        }

        // Store the new file and update the file_loa path
        $luaran->file_loa = $request->file('file_loa')->store('luaran', 'public'); // Store in 'luaran' directory
    }

    $luaran->save(); // Save the updated record

    return redirect()->back()->with('success', 'Luaran updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the Luaran record by ID
        $luaran = Luaran::findOrFail($id);
    
        // Delete the file if it exists
        if ($luaran->file_loa) {
            Storage::disk('public')->delete($luaran->file_loa);
        }
    
        // Delete the Luaran record
        $luaran->delete();
    
        return redirect()->back()->with('success', 'Luaran deleted successfully.');
    }

    

    public function updatestatus(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'status' => 'required',
        ]);

        // Cari luaran berdasarkan ID
        $luaran = Luaran::findOrFail($id);

        // Perbarui status luaran
        $luaran->status = $request->input('status');
        $luaran->save();

        // Cari LaporanAkhir terkait (cari berdasarkan laporanakhir_id dulu, jika tidak ada cari berdasarkan usulan_id)
        $laporanAkhir = null;

        if (!empty($luaran->laporanakhir_id)) {
            $laporanAkhir = LaporanAkhir::find($luaran->laporanakhir_id);
        }

        if (!$laporanAkhir) {
            $laporanAkhir = LaporanAkhir::where('usulan_id', $luaran->usulan_id)->first();
        }

        // Jika ada laporan akhir terkait, perbarui status sesuai status luaran
        if ($laporanAkhir) {
            if (strtolower($luaran->status) === strtolower('Terpenuhi')) {
                // Jika luaran terpenuhi, set laporan akhir menjadi approved
                $laporanAkhir->status = 'approved';
            } else {
                // Jika luaran tidak terpenuhi, set laporan akhir menjadi Ditolak
                $laporanAkhir->status = 'rejected';
            }
            $laporanAkhir->save();
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Status berhasil diperbarui menjadi: ' . ucfirst($luaran->status));
    }

public function export($jenis)
    {
        // Return the export file as an Excel download
        return Excel::download(new LuaranExport($jenis), 'luaran_' . $jenis . '.xlsx');
    }



}