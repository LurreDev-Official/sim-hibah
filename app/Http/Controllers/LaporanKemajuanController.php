<?php

namespace App\Http\Controllers;

use App\Models\LaporanKemajuan;
use App\Models\Usulan;
use Illuminate\Http\Request;

class LaporanKemajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil filter 'jenis' dari query string
        $jenis = $request->input('jenis');

        // Query data berdasarkan jenis jika ada filter
        $laporanKemajuan = LaporanKemajuan::when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        })->get();

        // Kembalikan ke view dengan data yang difilter
        return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($jenis)
{
    $user = auth()->user(); // Get the currently authenticated user

    // Check if the user has the 'Dosen' role
    if ($user->hasRole('Dosen')) {
        // Filter Usulan based on ketua_dosen_id and optionally the 'jenis' if provided
        $usulans = Usulan::where('ketua_dosen_id', $user->dosen->id)
        ->when($jenis, function($query, $jenis) {
            return $query->where('jenis_skema', $jenis);  // Filtering by jenis
        })
        ->where('status', 'approved') // Adding the status filter after the initial condition
        ->get();
    
        return view('laporan_kemajuan.create', compact('usulans', 'jenis'));
    } else {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membuat laporan.');
    }

    // Tampilkan form untuk menambah data
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'usulan_id' => 'required|exists:usulans,id',
        'dokumen_laporan_kemajuan' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'jenis' => 'required|in:penelitian,pengabdian',
    ]);

    // Save file with the original name
    if ($request->hasFile('dokumen_laporan_kemajuan')) {
        $file = $request->file('dokumen_laporan_kemajuan');
        
        // Get the original file name
        $fileName = $file->getClientOriginalName();
        
        // Store file in the 'public/uploads' folder with its original name
        $filePath = $file->storeAs('uploads', $fileName, 'public');
        
        // Add the file path to the validated data
        $validated['dokumen_laporan_kemajuan'] = $filePath;
    }
    $user = auth()->user(); 
    // Set the status to 'submitted'
    $validated['status'] = 'submitted';
    $validated['ketua_dosen_id'] = $user->dosen->id;
    // Create the LaporanKemajuan record in the database
    LaporanKemajuan::create($validated);

    // Redirect to the index page with a success message
   // Redirect to the show page with the jenis parameter
   return redirect()->route('laporan-kemajuan.show', ['jenis' => $validated['jenis']])
   ->with('success', 'Laporan kemajuan berhasil ditambahkan.');
  
}



    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        $user = auth()->user(); // Get the currently authenticated user
        // Default query for LaporanKemajuan
        $laporanKemajuanQuery = LaporanKemajuan::when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        });
    
        // Role-based logic to filter LaporanKemajuan
        if ($user->hasRole('Kepala LPPM')) {
            // Kepala LPPM can see all reports, no additional filtering needed
            $laporanKemajuan = $laporanKemajuanQuery->get();
        } elseif ($user->hasRole('Dosen')) {
            // Get the laporan kemajuan for the logged-in dosen
            $laporanKemajuan = $laporanKemajuanQuery->where('ketua_dosen_id', $user->dosen->id)->get();
            // Check if there are no reports
            if ($laporanKemajuan->isEmpty()) {
                // Option 1: Redirect to the create page with the jenis parameter
                return redirect()->route('laporan-kemajuan.create', ['jenis' => $jenis])
                                 ->with('info', 'Belum ada laporan kemajuan. Silakan buat laporan baru.');
            } else {
                // Option 2: Return the view with the laporan kemajuan data and an info message
                return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis'))
                       ->with('info', 'Belum ada laporan kemajuan. Silakan buat laporan baru.');
            }
        }
         elseif ($user->hasRole('Reviewer')) {
            // Reviewer can see only reports associated with the proposals they are reviewing
            $laporanKemajuan = $laporanKemajuanQuery->whereHas('usulan.penilaianReviewers', function ($query) use ($user) {
                $query->where('reviewer_id', $user->reviewer->id);
            })->get();
        } else {
            // If the user has no valid role, deny access or return an error
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    
        // Return the view with the filtered data
        return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Temukan laporan kemajuan berdasarkan id
        $laporanKemajuan = LaporanKemajuan::findOrFail($id);

        // Tampilkan form edit dengan data yang dipilih
        return view('laporan_kemajuan.edit', compact('laporanKemajuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'dokumen_laporan_kemajuan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
    ]);

    $laporanKemajuan = LaporanKemajuan::findOrFail($id);

    // Simpan file baru jika ada
    if ($request->hasFile('dokumen_laporan_kemajuan')) {
        // Hapus file lama jika ada
        if ($laporanKemajuan->dokumen_laporan_kemajuan && \Storage::disk('public')->exists($laporanKemajuan->dokumen_laporan_kemajuan)) {
            \Storage::disk('public')->delete($laporanKemajuan->dokumen_laporan_kemajuan);
        }

        // Simpan file baru
        $filePath = $request->file('dokumen_laporan_kemajuan')->store('uploads', 'public');
        $validated['dokumen_laporan_kemajuan'] = $filePath;
    }

    // Perbarui data
    $laporanKemajuan->update($validated);

    return redirect()->route('laporan-kemajuan.index')->with('success', 'Laporan kemajuan berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    // Find the laporan kemajuan record by ID
    $laporanKemajuan = LaporanKemajuan::findOrFail($id);

    // Delete the associated file if it exists
    if ($laporanKemajuan->dokumen_laporan_kemajuan) {
        Storage::delete('public/' . $laporanKemajuan->dokumen_laporan_kemajuan);
    }

    // Delete the laporan kemajuan record
    $laporanKemajuan->delete();
}


    
    

}