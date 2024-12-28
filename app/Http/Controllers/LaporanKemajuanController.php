<?php

namespace App\Http\Controllers;

use App\Models\LaporanKemajuan;
use App\Models\Usulan;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use App\Models\AnggotaMahasiswa;
use App\Models\Reviewer;
use App\Models\PenilaianReviewer;
use App\Models\FormPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\UsulanPerbaikan;

use PDF;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
class LaporanKemajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil filter 'jenis' dari query string
        

        $user = auth()->user(); // Ambil data user yang sedang login
    
        // Jika user memiliki role Kepala LPPM, tampilkan semua usulan
        // Check if the user has the 'Kepala LPPM' role
    if ($user->hasRole('Kepala LPPM')) {

       

    }
    
        // Jika user memiliki role Dosen, tampilkan usulan berdasarkan id_dosen
        elseif ($user->hasRole('Dosen')) {
            // Ambil data dosen terkait user yang login
            $dosen = Dosen::where('user_id', $user->id)->first();
           
            if ($dosen) {
                // Ambil semua usulan_id yang terkait dengan dosen dari tabel AnggotaDosen
                $usulanIds = AnggotaDosen::where('dosen_id', $dosen->id)->pluck('usulan_id');
        
                // Ambil semua usulan berdasarkan usulan_id yang ditemukan di AnggotaDosen
                $usulans = Usulan::whereIn('id', $usulanIds)
                                 ->where('jenis_skema', $jenis)
                                 ->paginate(10);
                                 $reviewers = Reviewer::with('user')->get();
                                 return view('usulan.index', compact('usulans', 'jenis','reviewers'));
        
            }

        }
    
        // Jika user memiliki role Reviewer, tampilkan usulan berdasarkan usulan_id dan reviewer_id
        elseif ($user->hasRole('Reviewer')) {
            // Cari usulan yang terkait dengan reviewer yang login
            $reviewer = Reviewer::where('user_id', $user->id)->first();
    
            // Cek apakah data reviewer ada
            if ($reviewer) {
                if ($reviewer) {
                    $data = PenilaianReviewer::with('usulan')
                        ->where('reviewer_id', $reviewer->id)
                        ->paginate(10);
                    
                }
                return view('usulan.index_reviewer', compact('data', 'jenis'));
            } else {
                return redirect()->back()->with('error', 'Reviewer ini tidak terdaftar pada usulan yang dipilih.');
            }
        }
    
        // Jika user tidak memiliki peran yang sesuai
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');


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
        
        $laporanKemajuanQuery = LaporanKemajuan::with('usulan','penilaianReviewers.reviewer')->when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        });
        // Role-based logic to filter LaporanKemajuan
        if ($user->hasRole('Kepala LPPM')) {
            // Kepala LPPM can see all reports, no additional filtering needed
            // Kembalikan ke view dengan data yang difilter
                $laporanKemajuan = LaporanKemajuan::with('penilaianReviewers.reviewer')->when($jenis, function ($query, $jenis) {
                    $query->where('jenis', $jenis);
                })->get();
    
            foreach ($laporanKemajuan as $usulan) {
                // Count the total number of reviewers
                $totalReviewers = $usulan->penilaianReviewers->count();
    
                // Count the number of reviewers who have accepted (status == 'Diterima')
                $acceptedReviewers = $usulan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();
    
                // If the total reviewers count matches the accepted reviewers count, set allReviewersAccepted to true
                $usulan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
            }
    
            // Fetch all reviewers (if you need to display them too)
            $reviewers = Reviewer::with('user')->get();
    
            return view('laporan_kemajuan.index', compact('laporanKemajuan','reviewers', 'jenis'));



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

       

                // Check if all reviewers for each usulan have accepted (status == 'Diterima')
                foreach ($laporanKemajuan as $usulan) {
                    // Count the total number of reviewers
                    $totalReviewers = $usulan->penilaianReviewers->count();

                    // Count the number of reviewers who have accepted (status == 'Diterima')
                    $acceptedReviewers = $usulan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();

                    // If the total reviewers count matches the accepted reviewers count, set allReviewersAccepted to true
                    $usulan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
                }


                return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis','usulan'))
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


public function kirim(Request $request)
{
    // Validasi permintaan
    $request->validate([
        'laporankemajuan_id' => 'required|integer',
        'reviewer_id' => 'required|array', // Reviwewer ID harus berupa array
        'reviewer_id.*' => 'required|integer', // Setiap reviewer_id harus berupa integer
        'action' => 'required|string|in:kirim,kirim_ulang', // Validasi action
    ]);

    // Ambil nilai input dari request
    $laporankemajuan_id = $request->input('laporankemajuan_id');
    // dd($laporankemajuan_id);
    $jenis = $request->input('jenis');
    $reviewerIds = $request->input('reviewer_id');
    $action = $request->input('action'); // Mendapatkan aksi tombol yang diklik

    // Temukan usulan berdasarkan ID
    $laporankemajuans = LaporanKemajuan::findOrFail($laporankemajuan_id);

    if ($action === 'kirim') {
        // Logika untuk "Kirim"
        if ($laporankemajuans->status === 'submitted') {
            $laporankemajuans->status = 'review';
            $laporankemajuans->save();

            // Loop untuk menambahkan reviewer baru
            $duplicateReviewers = [];
            foreach ($reviewerIds as $reviewerId) {
                $existingRecord = PenilaianReviewer::where('laporankemajuan_id', $laporankemajuan_id)
                                                   ->where('reviewer_id', $reviewerId)
                                                   ->first();

                if ($existingRecord) {
                    $duplicateReviewers[] = $reviewerId;
                } else {
                    PenilaianReviewer::create([
                        'laporankemajuan_id' => $laporankemajuan_id,
                        'status_penilaian' => 'Belum Dinilai',
                        'proses_penilaian' => 'Laporan Kemajuan',
                        'urutan_penilaian' => 2,
                        'reviewer_id' => $reviewerId,
                        'total_nilai'=>'0'
                    ]);
                }
            }

            // Buat pesan notifikasi jika ada duplikasi
            if (!empty($duplicateReviewers)) {
                return redirect()->back()->with('error', 'Beberapa reviewer sudah ditambahkan sebelumnya.');
            }

            return redirect()->back()->with('success', 'Usulan berhasil dikirim ke reviewer.');
        } else {
            return redirect()->back()->with('error', 'Usulan sudah dikirim sebelumnya.');
        }
    } elseif ($action === 'kirim_ulang') {
        // Logika untuk "Kirim Ulang"
        if ($laporankemajuans->status === 'review') {
            // Hapus reviewer sebelumnya
            PenilaianReviewer::where('laporankemajuan_id', $laporankemajuan_id)->delete();

            // Tambahkan reviewer baru
            foreach ($reviewerIds as $reviewerId) {
                PenilaianReviewer::create([
                    'laporankemajuan_id' => $laporankemajuan_id,
                    'status_penilaian' => 'Belum Dinilai',
                    'reviewer_id' => $reviewerId,
                    'total_nilai'=>'0'
                ]);
            }

            return redirect()->back()->with('success', 'Usulan berhasil dikirim ulang ke reviewer.');
        } else {
            return redirect()->back()->with('error', 'Usulan belum dalam status review untuk dikirim ulang.');
        }
    }

    return redirect()->back()->with('error', 'Aksi tidak dikenali.');
}





    
    

}