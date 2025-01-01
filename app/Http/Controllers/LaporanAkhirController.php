<?php

namespace App\Http\Controllers;

use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usulan;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use App\Models\AnggotaMahasiswa;
use App\Models\Reviewer;
use App\Models\PenilaianReviewer;
use App\Models\FormPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\UsulanPerbaikan;
use Illuminate\Support\Facades\Storage;
use PDF;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

use Illuminate\Support\Facades\Crypt;
class LaporanAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan jenis (opsional)
        $jenis = $request->input('jenis');
        $laporanAkhirs = LaporanAkhir::when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        })->get();

        // Tampilkan ke view
        return view('laporan_akhir.index', compact('laporanAkhirs', 'jenis'));
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
        
            return view('laporan_akhir.create', compact('usulans', 'jenis'));
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membuat laporan.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the input
    $validated = $request->validate([
        'usulan_id' => 'required|exists:usulans,id',
        'dokumen_laporan_akhir' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'jenis' => 'required|in:penelitian,pengabdian',
    ]);




    // Check if usulan_id already exists in LaporanKemajuan
    $existingLaporanKemajuan = LaporanAkhir::where('usulan_id', $validated['usulan_id'])->first();

    if ($existingLaporanKemajuan) {
        // If there's already a LaporanKemajuan for the given usulan_id
        return back()->with('error', 'Laporan Akhir untuk usulan ini sudah ada.');
    }else{


    // Save file with the original name
    if ($request->hasFile('dokumen_laporan_akhir')) {
        $file = $request->file('dokumen_laporan_akhir');
        
        // Get the original file name
        $fileName = $file->getClientOriginalName();
        
        // Store file in the 'public/uploads' folder with its original name
        $filePath = $file->storeAs('laporan_akhir', $fileName, 'public');
        
        // Add the file path to the validated data
        $validated['dokumen_laporan_akhir'] = $filePath;
    }
    $getUsulan = Usulan::findOrFail($validated['usulan_id']);
    $validated['ketua_dosen_id'] = $getUsulan->ketua_dosen_id;
    $validated['status'] = 'submitted';
    // Create the LaporanAkhir record in the database
    LaporanAkhir::create($validated);


   // Redirect to the show page with the jenis parameter
   return redirect()->route('laporan-akhir.show', ['jenis' => $validated['jenis']])
   ->with('success', 'Laporan Akhir berhasil ditambahkan.');
}
}

public function kirim(Request $request)
{
    // Validasi permintaan
    $request->validate([
        'laporanakhir_id' => 'required|integer',
        'reviewer_id' => 'required|array', // Reviwewer ID harus berupa array
        'reviewer_id.*' => 'required|integer', // Setiap reviewer_id harus berupa integer
        'action' => 'required|string|in:kirim,kirim_ulang', // Validasi action
    ]);

    // Ambil nilai input dari request
    $laporanakhir_id = $request->input('laporanakhir_id');
    // dd($laporanakhir_id);
    $jenis = $request->input('jenis');
    $reviewerIds = $request->input('reviewer_id');
    $action = $request->input('action'); // Mendapatkan aksi tombol yang diklik

    // Temukan usulan berdasarkan ID
    $laporanAkhir = LaporanAkhir::findOrFail($laporanakhir_id);

    if ($action === 'kirim') {
        // Logika untuk "Kirim"
        if ($laporanAkhir->status === 'submitted') {
            $laporanAkhir->status = 'review';
            $laporanAkhir->save();

            // Loop untuk menambahkan reviewer baru
            $duplicateReviewers = [];
            foreach ($reviewerIds as $reviewerId) {
                $existingRecord = PenilaianReviewer::where('laporanakhir_id', $laporanakhir_id)
                                                   ->where('reviewer_id', $reviewerId)
                                                   ->first();

                if ($existingRecord) {
                    $duplicateReviewers[] = $reviewerId;
                } else {
                    PenilaianReviewer::create([
                        'laporanakhir_id' => $laporanakhir_id,
                        'status_penilaian' => 'Belum Dinilai',
                        'proses_penilaian' => 'Laporan Akhir',
                        'urutan_penilaian' => 3,
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
        if ($laporanAkhir->status === 'review') {
            // Hapus reviewer sebelumnya
            PenilaianReviewer::where('laporanakhir_id', $laporanakhir_id)->delete();

            // Tambahkan reviewer baru
            foreach ($reviewerIds as $reviewerId) {
                PenilaianReviewer::create([
                    'laporanakhir_id' => $laporanakhir_id,
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



    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        $user = auth()->user(); // Get the currently authenticated user
        
        $laporanAkhirQuery = laporanAkhir::with('usulan','penilaianReviewers.reviewer')->when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        });
        // Role-based logic to filter laporanAkhir
        if ($user->hasRole('Kepala LPPM')) {
            // Kepala LPPM can see all reports, no additional filtering needed
            // Kembalikan ke view dengan data yang difilter
                $laporanAkhir = laporanAkhir::with('penilaianReviewers.reviewer')->when($jenis, function ($query, $jenis) {
                    $query->where('jenis', $jenis);
                })->get();
    
            foreach ($laporanAkhir as $usulan) {
                // Count the total number of reviewers
                $totalReviewers = $usulan->penilaianReviewers->count();
    
                // Count the number of reviewers who have accepted (status == 'Diterima')
                $acceptedReviewers = $usulan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();
    
                // If the total reviewers count matches the accepted reviewers count, set allReviewersAccepted to true
                $usulan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
            }
    
            // Fetch all reviewers (if you need to display them too)
            $reviewers = Reviewer::with('user')->get();
    
            return view('laporan_akhir.index', compact('laporanAkhir','reviewers', 'jenis'));



        } elseif ($user->hasRole('Dosen')) {
            // Get the Laporan Akhir for the logged-in dosen
            // Ambil data usulan berdasarkan ketua dosen dan jenis skema
            $getUsulan = Usulan::where('ketua_dosen_id', $user->dosen->id)
                ->where('jenis_skema', $jenis)
                ->get(); // Menggunakan get() karena bisa ada lebih dari satu usulan
        
            // Cek apakah ada laporan kemajuan untuk usulan tersebut
            foreach ($getUsulan as $usulan) {
                $existingLaporan = laporanAkhir::where('usulan_id', $usulan->id)->first();
        
                // Jika laporan kemajuan tidak ditemukan, arahkan ke halaman pembuatan laporan baru
                if (!$existingLaporan) {
                    return redirect()->route('laporan-akhir.create', ['jenis' => $jenis])
                        ->with('info', 'Belum ada laporan kemajuan untuk usulan ini. Silakan buat laporan baru.');
                }
            }


            $laporanAkhir = $laporanAkhirQuery->where('ketua_dosen_id', $user->dosen->id)->get();
            // Check if there are no reports
            if ($laporanAkhir->isEmpty()) {
                // Option 1: Redirect to the create page with the jenis parameter
                return redirect()->route('laporan-akhir.create', ['jenis' => $jenis])
                                 ->with('info', 'Belum ada Laporan Akhir. Silakan buat laporan baru.');
            } else {
                // Option 2: Return the view with the Laporan Akhir data and an info message

       

                // Check if all reviewers for each usulan have accepted (status == 'Diterima')
                foreach ($laporanAkhir as $usulan) {
                    // Count the total number of reviewers
                    $totalReviewers = $usulan->penilaianReviewers->count();

                    // Count the number of reviewers who have accepted (status == 'Diterima')
                    $acceptedReviewers = $usulan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();

                    // If the total reviewers count matches the accepted reviewers count, set allReviewersAccepted to true
                    $usulan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
                }


                return view('laporan_akhir.index', compact('laporanAkhir', 'jenis','usulan'))
                       ->with('info', 'Belum ada Laporan Akhir. Silakan buat laporan baru.');
            }
        }
         elseif ($user->hasRole('Reviewer')) {
            // Reviewer can see only reports associated with the proposals they are reviewing
            $laporanAkhir = $laporanAkhirQuery->whereHas('usulan.penilaianReviewers', function ($query) use ($user) {
                $query->where('reviewer_id', $user->reviewer->id);
            })->get();
        } else {
            // If the user has no valid role, deny access or return an error
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    
        // Return the view with the filtered data
        return view('laporan_akhir.index', compact('laporanAkhir', 'jenis'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanAkhir $laporanAkhir)
    {
        // Tampilkan form untuk mengedit laporan akhir
        return view('laporan_akhir.edit', compact('laporanAkhir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanAkhir $laporanAkhir)
    {
        // Validasi data input
        $validated = $request->validate([
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'usulan_id' => 'required|exists:usulans,id',
            'dokumen_laporan_akhir' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'jenis' => 'required|in:Penelitian,Pengabdian',
            'status' => 'required|string',
        ]);

        // Perbarui file jika ada
        if ($request->hasFile('dokumen_laporan_akhir')) {
            // Hapus file lama jika ada
            if ($laporanAkhir->dokumen_laporan_akhir && \Storage::disk('public')->exists($laporanAkhir->dokumen_laporan_akhir)) {
                \Storage::disk('public')->delete($laporanAkhir->dokumen_laporan_akhir);
            }
            // Simpan file baru
            $validated['dokumen_laporan_akhir'] = $request->file('dokumen_laporan_akhir')->store('laporan_akhir', 'public');
        }

        // Perbarui data di database
        $laporanAkhir->update($validated);

        return redirect()->route('laporan-akhir.index')->with('success', 'Laporan akhir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanAkhir $laporanAkhir)
    {
        // Hapus file dari storage jika ada
        if ($laporanAkhir->dokumen_laporan_akhir && \Storage::disk('public')->exists($laporanAkhir->dokumen_laporan_akhir)) {
            \Storage::disk('public')->delete($laporanAkhir->dokumen_laporan_akhir);
        }

        // Hapus data dari database
        $laporanAkhir->delete();

        return redirect()->route('laporan-akhir.index')->with('success', 'Laporan akhir berhasil dihapus.');
    }


    public function perbaikiRevisi($jenis, $id)
{
    // Fetch related penilaianReviewer and indikatorPenilaians
    $penilaianReviewer = PenilaianReviewer::where('laporanakhir_id', $id)->firstOrFail();
    $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
        ->whereIn('kriteria_id', KriteriaPenilaian::where('jenis', $jenis)->where('proses', 'Laporan Akhir')->pluck('id'))
        ->get();

    // Fetch the related LaporanKemajuan (if exists)
    $laporanAkhir = LaporanAkhir::where('id', $id)
        ->first(); // Fetch the existing LaporanAkhir record
    // Return the perbaiki revisi view with the additional laporanAkhir variable
    return view('laporan_akhir.perbaiki_revisi', compact('penilaianReviewer', 'indikatorPenilaians', 'laporanAkhir'));
}



public function simpanPerbaikan(Request $request, $id)
{
    // Validate input
    $request->validate([
        'file_perbaikan' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
    ]);
    
    $laporanAkhir = LaporanAkhir::findOrFail($id);
    
    // Delete the old file if it exists
    if ($laporanAkhir->dokumen_laporan_akhir) {
        Storage::disk('public')->delete($laporanAkhir->dokumen_laporan_akhir);
    }
    
    
    // Fetch the related PenilaianReviewer
    $penilaianReviewer = PenilaianReviewer::where('laporanakhir_id', $id)->firstOrFail();
    
    // Handle file upload
    $file = $request->file('file_perbaikan');
    
    // Get the original file name
    $originalFileName = $file->getClientOriginalName(); 
    
    // Define the storage path and store the file with its original name
    $filePath = $file->storeAs('laporan_akhir', $originalFileName, 'public'); // Store with original name
    
   
    // Update the LaporanKemajuan record with the new file path
    $laporanAkhir->dokumen_laporan_akhir = $filePath;
    $laporanAkhir->status = 'waiting approved';
    $laporanAkhir->save();

    if ($laporanAkhir) {
        $penilaianReviewer->status_penilaian = 'sudah diperbaiki';
        $penilaianReviewer->save();
    }
  $jenis = $laporanAkhir->jenis;
  return redirect()->back()->with('success', 'Berhasil di simpan');
}






    public function updateStatus($id, Request $request)
{
    // Find the Usulan by ID
    $laporanAkhir = LaporanAkhir::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'status' => 'required|in:approved,rejected', // Only allow 'approved' or 'rejected' status
    ]);

    // Update the status of the Usulan
    $laporanAkhir->status = $validated['status'];
    $laporanAkhir->save(); // Save the updated status
    return redirect()->back()->with('success', 'Berhasil di simpan.');
}
 


public function cetakBuktiACC($id)
{
    // Ambil data usulan dengan relasi terkait
    $laporanAkhir = LaporanAkhir::with(['usulan.ketuaDosen', 'usulan.anggotaDosen.dosen.user', 'usulan.anggotaMahasiswa'])
                    ->findOrFail($id);

    // Ambil timestamp Unix
    $timestamp = time(); // Mendapatkan timestamp Unix saat ini

    // Generate Barcode 2D (QR Code) menggunakan milon/barcode
    // Menggabungkan ID usulan dan timestamp ke dalam QR Code
    $dataToEncode = [
        'id' => $laporanAkhir->id,
        'timestamp' => $timestamp,
    ];
    
    // Encode data menjadi JSON string
    $jsonData = json_encode($dataToEncode);

    // Encrypt JSON data before encoding it to QR Code
    $encryptedData = Crypt::encryptString(json_encode($dataToEncode));

// Generate QR Code containing encrypted data
     $barcode2D = \DNS2D::getBarcodePNG($encryptedData, 'QRCODE');

  
    // Simpan Barcode 2D ke file sementara di storage
    $barcode2DPath = 'public/images/ttd_usulan_2D_' . $laporanAkhir->id . '.png';
    \Storage::put($barcode2DPath, base64_decode($barcode2D)); // Decode base64 sebelum disimpan

    // Dapatkan URL untuk QR Code yang disimpan
    $barcode2DUrl = \Storage::url($barcode2DPath); // Mendapatkan URL yang dapat diakses publik

    // Mengonversi QR Code ke Base64
    $barcodeBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(storage_path('app/' . $barcode2DPath)));

    // Generate PDF
    $pdf = \PDF::loadView('laporan_akhir.bukti_acc', [
        'laporanAkhir' => $laporanAkhir,
        'barcodeBase64' => $barcodeBase64, // Kirim Base64 barcode ke view
    ]);
    // Kembalikan file PDF untuk diunduh
    return $pdf->download('bukti_acc_' . $laporanAkhir->id . '.pdf');
}


}
