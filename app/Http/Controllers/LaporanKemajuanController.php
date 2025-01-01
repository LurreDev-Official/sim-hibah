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
use Illuminate\Support\Facades\Storage;
use PDF;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

use Illuminate\Support\Facades\Crypt;
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

     // Check if usulan_id already exists in LaporanKemajuan
     $existingLaporanKemajuan = LaporanKemajuan::where('usulan_id', $validated['usulan_id'])->first();

     if ($existingLaporanKemajuan) {
         // If there's already a LaporanKemajuan for the given usulan_id
         return back()->with('error', 'Laporan Kemajuan untuk usulan ini sudah ada.');
     }else{

        
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
}



    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        $user = auth()->user(); // Get the currently authenticated user
        
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
            // Ambil data usulan berdasarkan ketua dosen dan jenis skema
            $getUsulan = Usulan::where('ketua_dosen_id', $user->dosen->id)
                ->where('jenis_skema', $jenis)
                ->get(); // Menggunakan get() karena bisa ada lebih dari satu usulan
        
            // Cek apakah ada laporan kemajuan untuk usulan tersebut
            foreach ($getUsulan as $usulan) {
                $existingLaporan = LaporanKemajuan::where('usulan_id', $usulan->id)->first();
        
                // Jika laporan kemajuan tidak ditemukan, arahkan ke halaman pembuatan laporan baru
                if (!$existingLaporan) {
                    return redirect()->route('laporan-kemajuan.create', ['jenis' => $jenis])
                        ->with('info', 'Belum ada laporan kemajuan untuk usulan ini. Silakan buat laporan baru.');
                }
            }
        
            // Ambil laporan kemajuan setelah pengecekan
            $laporanKemajuan = LaporanKemajuan::with('usulan', 'penilaianReviewers.reviewer')
                ->whereIn('usulan_id', $getUsulan->pluck('id')) // Mengambil laporan kemajuan berdasarkan usulan_id yang ditemukan
                ->get();
        
            // Check if all reviewers for each usulan have accepted (status == 'Diterima')
            foreach ($laporanKemajuan as $laporan) {
                // Hitung jumlah reviewer
                $totalReviewers = $laporan->penilaianReviewers->count();
        
                // Hitung jumlah reviewer yang diterima (status == 'Diterima')
                $acceptedReviewers = $laporan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();
        
                // Jika jumlah reviewer yang diterima sama dengan total reviewer, set allReviewersAccepted ke true
                $laporan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
            }
        
            // Kembalikan tampilan dengan data laporan kemajuan
            return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis'))
                ->with('info', 'Laporan kemajuan baru telah dibuat untuk usulan yang belum ada laporan kemajuannya.');
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



public function perbaikiRevisi($jenis, $id)
{
    // Fetch related penilaianReviewer and indikatorPenilaians
    $penilaianReviewer = PenilaianReviewer::where('laporankemajuan_id', $id)->firstOrFail();
    $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
        ->whereIn('kriteria_id', KriteriaPenilaian::where('jenis', $jenis)->where('proses', 'Laporan Kemajuan')->pluck('id'))
        ->get();

    // Fetch the related LaporanKemajuan (if exists)
    $laporanKemajuan = LaporanKemajuan::where('id', $id)
        ->first(); // Fetch the existing LaporanKemajuan record
    // Return the perbaiki revisi view with the additional laporanKemajuan variable
    return view('laporan_kemajuan.perbaiki_revisi', compact('penilaianReviewer', 'indikatorPenilaians', 'laporanKemajuan'));
}



public function simpanPerbaikan(Request $request, $id)
{
    // Validate input
    $request->validate([
        'file_perbaikan' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
    ]);
    
    // Fetch the related PenilaianReviewer
    $penilaianReviewer = PenilaianReviewer::where('laporankemajuan_id', $id)->firstOrFail();
    
    // Handle file upload
    $file = $request->file('file_perbaikan');
    
    // Get the original file name
    $originalFileName = $file->getClientOriginalName(); 
    
    // Define the storage path and store the file with its original name
    $filePath = $file->storeAs('laporan_perbaikans', $originalFileName, 'public'); // Store with original name
    
    $laporanKemajuan = LaporanKemajuan::findOrFail($id);
    
    // Delete the old file if it exists
    if ($laporanKemajuan->dokumen_laporan_kemajuan) {
        Storage::disk('public')->delete($laporanKemajuan->dokumen_laporan_kemajuan);
    }
    
    // Update the LaporanKemajuan record with the new file path
    $laporanKemajuan->dokumen_laporan_kemajuan = $filePath;
    $laporanKemajuan->status = 'waiting approved';
    $laporanKemajuan->save();

    if ($laporanKemajuan) {
        $penilaianReviewer->status_penilaian = 'sudah diperbaiki';
        $penilaianReviewer->save();
    }
  $jenis = $laporanKemajuan->jenis;
    // Redirect back with a success message
  return redirect()->route('laporan-kemajuan.show', ['jenis' => $jenis])->with('success', 'Berhasil di simpan');
}



public function updateStatus($id, Request $request)
{
    // Find the Usulan by ID
    $laporanKemajuan = LaporanKemajuan::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'status' => 'required|in:approved,rejected', // Only allow 'approved' or 'rejected' status
    ]);

    // Update the status of the Usulan
    $laporanKemajuan->status = $validated['status'];
    $laporanKemajuan->save(); // Save the updated status
    return redirect()->back()->with('success', 'Berhasil di simpan.');
}
 


public function cetakBuktiACC($id)
{
    // Ambil data usulan dengan relasi terkait
    $laporanKemajuan = LaporanKemajuan::with(['usulan.ketuaDosen', 'usulan.anggotaDosen.dosen.user', 'usulan.anggotaMahasiswa'])
                    ->findOrFail($id);

    // Ambil timestamp Unix
    $timestamp = time(); // Mendapatkan timestamp Unix saat ini

    // Generate Barcode 2D (QR Code) menggunakan milon/barcode
    // Menggabungkan ID usulan dan timestamp ke dalam QR Code
    $dataToEncode = [
        'id' => $laporanKemajuan->id,
        'timestamp' => $timestamp,
    ];
    
    // Encode data menjadi JSON string
    $jsonData = json_encode($dataToEncode);

    // Encrypt JSON data before encoding it to QR Code
    $encryptedData = Crypt::encryptString(json_encode($dataToEncode));

// Generate QR Code containing encrypted data
     $barcode2D = \DNS2D::getBarcodePNG($encryptedData, 'QRCODE');

  
    // Simpan Barcode 2D ke file sementara di storage
    $barcode2DPath = 'public/images/ttd_usulan_2D_' . $laporanKemajuan->id . '.png';
    \Storage::put($barcode2DPath, base64_decode($barcode2D)); // Decode base64 sebelum disimpan

    // Dapatkan URL untuk QR Code yang disimpan
    $barcode2DUrl = \Storage::url($barcode2DPath); // Mendapatkan URL yang dapat diakses publik

    // Mengonversi QR Code ke Base64
    $barcodeBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(storage_path('app/' . $barcode2DPath)));

    // Generate PDF
    $pdf = \PDF::loadView('laporan_kemajuan.bukti_acc', [
        'laporanKemajuan' => $laporanKemajuan,
        'barcodeBase64' => $barcodeBase64, // Kirim Base64 barcode ke view
    ]);
    // Kembalikan file PDF untuk diunduh
    return $pdf->download('bukti_acc_' . $laporanKemajuan->id . '.pdf');
}

}