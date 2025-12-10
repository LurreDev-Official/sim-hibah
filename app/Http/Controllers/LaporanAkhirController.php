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
use App\Models\Luaran;
use App\Models\Periode;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use App\Models\LaporanKemajuan;
use App\Models\TemplateDokumen;

use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

use App\Exports\LaporanAkhirExport;

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
            $laporakemajuans = LaporanKemajuan::where('ketua_dosen_id', $user->dosen->id)
            ->when($jenis, function($query, $jenis) {
                return $query->where('jenis', $jenis);  // Filtering by jenis
            })
            ->where('status', 'approved') // Adding the status filter after the initial condition
            ->get();
            $getTemplate = TemplateDokumen::where('proses', 'Laporan Akhir')->where('skema', $jenis)->first();
            return view('laporan_akhir.create', compact('laporakemajuans', 'jenis','getTemplate'));
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
        'dokumen_laporan_akhir' => 'required|file|mimes:pdf|max:10120', // Max 5MB
        'jenis' => 'required|in:penelitian,pengabdian',
    ]);

    // dd($validated['jenis']);
    $existingLaporanAkhir = LaporanAkhir::where('usulan_id', $validated['usulan_id'])
    ->where('jenis', $request->jenis)->first();

    if ($existingLaporanAkhir) {
        // If there's already a LaporanAkhir for the given usulan_id
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
            // Ambil data dosen terkait user yang login
              // Ambil data dosen terkait user yang login
              $dosen = Dosen::where('user_id', $user->id)->first();

              if ($dosen) {
                  // Ambil ID usulan yang terkait dengan dosen (sebagai ketua atau anggota)
                  $usulanIds = Usulan::where('ketua_dosen_id', $dosen->id) // Ketua dosen
                      ->orWhereHas('anggotaDosen', function ($query) use ($dosen) {
                          $query->where('dosen_id', $dosen->id); // Anggota dosen
                      })
                      ->pluck('id'); // Ambil hanya kolom ID
  
                  // Ambil laporan kemajuan berdasarkan usulan_id yang ditemukan
                  $laporanAkhir = LaporanAkhir::with(['usulan', 'penilaianReviewers.reviewer'])
                      ->whereIn('usulan_id', $usulanIds)
                    ->where('jenis', $jenis)
                      ->get();
  
                  // Filter jenis laporan (opsional)
                  if ($jenis) {
                      $laporanAkhir = $laporanAkhir->where('jenis', $jenis);
                  }
  
                  // Check if all reviewers for each laporan kemajuan have accepted (status == 'Diterima')
                  foreach ($laporanAkhir as $laporan) {
                      // Hitung jumlah reviewer
                      $totalReviewers = $laporan->penilaianReviewers->count();
  
                      // Hitung jumlah reviewer yang diterima (status_penilaian == 'Diterima')
                      $acceptedReviewers = $laporan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();
  
                      // Jika jumlah reviewer yang diterima sama dengan total reviewer, set allReviewersAccepted ke true
                      $laporan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
                  }
            
            // Kembalikan tampilan dengan data laporan akhir
            $laporanExists = LaporanKemajuan::whereIn('usulan_id', $usulanIds)
                ->where('jenis', $jenis)
                ->exists();

            

            // Jika laporan kemajuan sudah ada, redirect dengan pesan
            if ($laporanExists) {
                $usulans = Usulan::whereIn('id', $usulanIds)
                ->where('status', 'approved')
                ->where('jenis_skema', $jenis)
                ->get();
                return view('laporan_akhir.index', compact('laporanAkhir', 'jenis','usulans','dosen')) ;
                    // ->with('info', 'Laporan kemajuan baru telah dibuat untuk usulan yang belum ada laporan kemajuannya.');
            }else{
                // dd($usulanIds);
                $laporakemajuans = LaporanKemajuan::with('usulan')->whereIn('usulan_id', $usulanIds)
                ->where('status', 'approved')
                ->where('jenis', $jenis)
                ->get();
                // Ambil template laporan kemajuan (opsional)
                $getTemplate = TemplateDokumen::where([
            ['proses', '=', 'Laporan Akhir'],
            ['skema', '=', $jenis]
        ])->first();
                return view('laporan_akhir.create', compact('laporakemajuans', 'jenis', 'getTemplate'));
            }

             
        }
         elseif ($user->hasRole('Reviewer')) {
            // Reviewer can see only reports associated with the proposals they are reviewing
            $laporanAkhir = $laporanAkhirQuery->whereHas('usulan.penilaianReviewers', function ($query) use ($user) {
                $query->where('reviewer_id', $user->reviewer->id);
            })->get();
        return view('laporan_akhir.index', compact('laporanAkhir', 'jenis'));

        } else {
            // If the user has no valid role, deny access or return an error
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

    }
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
        // Validasi data input - hanya dokumen yang bisa diupdate
        $validated = $request->validate([
            'dokumen_laporan_akhir' => 'required|file|mimes:pdf|max:10120', // Max 10MB
        ]);

        // Perbarui file jika ada
        if ($request->hasFile('dokumen_laporan_akhir')) {
            $file = $request->file('dokumen_laporan_akhir');
            
            // Hapus file lama jika ada
            if ($laporanAkhir->dokumen_laporan_akhir && \Storage::disk('public')->exists($laporanAkhir->dokumen_laporan_akhir)) {
                \Storage::disk('public')->delete($laporanAkhir->dokumen_laporan_akhir);
            }
            
            // Get the original file name
            $fileName = $file->getClientOriginalName();
            
            // Store file in the 'public/uploads' folder with its original name
            $filePath = $file->storeAs('laporan_akhir', $fileName, 'public');
            
            // Update dokumen laporan akhir
            $laporanAkhir->dokumen_laporan_akhir = $filePath;
            
            // Update status menjadi submitted jika sebelumnya rejected atau draft
            if (in_array($laporanAkhir->status, ['rejected', 'draft'])) {
                $laporanAkhir->status = 'submitted';
            }
            
            $laporanAkhir->save();
        }

        // Redirect kembali ke halaman index dengan jenis yang sesuai
        return redirect()->route('laporan-akhir.show', ['jenis' => $laporanAkhir->jenis])
            ->with('success', 'Dokumen laporan akhir berhasil diperbarui.');
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

    // Fetch the related LaporanAkhir (if exists)
    $laporanAkhir = LaporanAkhir::where('id', $id)
        ->first(); // Fetch the existing LaporanAkhir record
    // Return the perbaiki revisi view with the additional laporanAkhir variable
    return view('laporan_akhir.perbaiki_revisi', compact('penilaianReviewer', 'indikatorPenilaians', 'laporanAkhir'));
}



public function simpanPerbaikan(Request $request, $id)
{
    // Validate input
    $request->validate([
        'file_perbaikan' => 'required|file|mimes:pdf|max:10120', // Max 5MB
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
    
   
    // Update the LaporanAkhir record with the new file path
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

public function cetakLembarPengesahan($id)
{

    $laporanAkhir = LaporanAkhir::with(['usulan.ketuaDosen', 'usulan.anggotaDosen.dosen.user', 'usulan.anggotaMahasiswa'])
                    ->findOrFail($id);

    $usulan_id = $laporanAkhir->usulan_id;

    // Ambil data usulan dengan relasi terkait
    $usulan = Usulan::with(['ketuaDosen.user', 'anggotaDosen.dosen.user'])
                    ->findOrFail($usulan_id);

     $usulanPerbaikan = UsulanPerbaikan::where('usulan_id', $usulan_id)->first();
     // Generate URL lengkap untuk dokumen
    $dokumenPath = $usulanPerbaikan->dokumen_usulan ?? '';
    $dokumenUrl = url($dokumenPath); // Base URL + path dokumen

    // Generate QR Code dalam format SVG
    $qrCodeSVG = \DNS2D::getBarcodeSVG($dokumenUrl, 'QRCODE', 3, 3); // Gunakan SVG
        // Filter anggota dosen dengan status_anggota = 'anggota'
     $filteredAnggotaDosen = $usulan->anggotaDosen->filter(function ($anggota) {
        return $anggota->status_anggota === 'anggota';
    });

    // Hitung jumlah total anggota dosen dan mahasiswa
    $jumlahAnggotaDosen = $filteredAnggotaDosen->count();
    $jumlahAnggotaMahasiswa = AnggotaMahasiswa::where('usulan_id', $usulan_id)->count();
    // $jumlahAnggotaTotal = $jumlahAnggotaDosen + $jumlahAnggotaMahasiswa;
    $jumlahAnggotaTotal = $jumlahAnggotaDosen;



    // Ambil data anggota mahasiswa dari model AnggotaMahasiswa
    $anggotaMahasiswa = AnggotaMahasiswa::where('usulan_id', $usulan_id)->get();

    // Ambil periode yang aktif
    $periode = Periode::where('is_active', true)->first();

    // Data dekan berdasarkan fakultas
    $listdekan = [
        'Fakultas Agama Islam' => [
            'nama' => 'Dr. Jasminto, M.Pd.I., M.Ag',
            'nidn' => '2112038101',
        ],
        'Fakultas Ilmu Pendidikan' => [
            'nama' => 'Dr. Resdianto Permata Raharjo, M.Pd',
            'nidn' => '0701109201',
        ],
        'Fakultas Teknik' => [
            'nama' => 'Dr. Ir. Nur Kholis, S.T., M.T.',
            'nidn' => '0021057204',
        ],
        'Fakultas Teknologi Informasi' => [
            'nama' => 'Dr. Aries Dwi Indriyanti, S.Kom., M.Kom',
            'nidn' => '0012048006',
        ],
        'Fakultas Ekonomi' => [
            'nama' => 'Dr. Tony Seno Aji, S.E., M.E',
            'nidn' => '0024097803',
        ],
    ];

    // Cocokkan dekan berdasarkan fakultas ketua dosen
    $fakultasKetua = $usulan->ketuaDosen->fakultas->name ?? 'Unknown';
    $dekan = $listdekan[$fakultasKetua] ?? ['nama' => 'Unknown', 'nidn' => 'Unknown'];

    $kepalaLPPM = [
        'nama' => 'Prof. Dr. Udjang Pairin M. Basir, M.Pd',
        'nidn' => '0010065707',
    ];

    // Format data usulan untuk dikirim ke view
    $formattedUsulan = [
        'judul_usulan' => $usulan->judul_usulan,
        'lokasi_penelitian' => $usulan->lokasi_penelitian,
        'tingkat_kecukupan_teknologi' => $usulan->tingkat_kecukupan_teknologi,
        'nama_mitra' => $usulan->nama_mitra,
        'bidang_mitra' => $usulan->bidang_mitra,
        'lokasi_mitra' => $usulan->lokasi_mitra,
        'jarak_pt_ke_lokasi_mitra' => $usulan->jarak_pt_ke_lokasi_mitra,
        'luaran' => $usulan->luaran,
        'ketuaDosen' => [
            'name' => $usulan->ketuaDosen->user->name ?? 'Tidak Tersedia',
            'nidn' => $usulan->ketuaDosen->nidn ?? '-',
            'jabatan' => $usulan->ketuaDosen->jabatan ?? '-',
            'prodi' => $usulan->ketuaDosen->prodi->name ?? '-',
        ],
        'anggotaDosen' => $usulan->anggotaDosen
    ->filter(function ($anggota) {
        return $anggota->status_anggota === 'anggota'; // Filter hanya yang status_anggota = 'anggota'
    })
    ->map(function ($anggota) {
        return [
            'name' => $anggota->dosen->user->name ?? 'Tidak Tersedia',
            'nidn' => $anggota->dosen->nidn ?? '-',
        ];
    })
    ->toArray(),

        'anggotaMahasiswa' => $anggotaMahasiswa->map(function ($anggota) {
            return [
                'name' => $anggota->nama_lengkap ?? 'Tidak Tersedia',
                'nim' => $anggota->nim ?? '-',
            ];
        })->toArray(),

        'jumlahAnggota' => $jumlahAnggotaTotal,
        'dokumen_usulan' => $usulanPerbaikan->dokumen_usulan,
    ];
    // Return view dengan data
    return view('laporan_akhir.printpengasahan', compact('formattedUsulan', 'usulan','periode', 'dekan', 'kepalaLPPM','qrCodeSVG'));
}






    public function updateStatus($id, Request $request)
{
    // Find the LaporanAkhir by ID
    $laporanAkhir = LaporanAkhir::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'status' => 'required|in:approved,rejected', // Only allow 'approved' or 'rejected' status
    ]);

    // Update the status of the LaporanAkhir
    // If the status is 'approved', update the corresponding Luaran
    if ($validated['status'] == 'approved') {
        $existingLuaran = Luaran::where('usulan_id', $laporanAkhir->usulan_id)
                                ->where('type', 'Laporan akhir' ) // Make sure type is unique
                                ->first();

        if ($existingLuaran) {
            // Update the Luaran status to 'Terpenuhi' and set the URL
            $existingLuaran->status = 'Terpenuhi';
            $existingLuaran->url = url('storage/' . $laporanAkhir->dokumen_laporan_akhir); // Assuming you have the document URL
            $existingLuaran->save(); // Save the updated Luaran
            $laporanAkhir->status = $validated['status'];
             $laporanAkhir->save(); // Save the updated status
        }
    }else {
         $laporanAkhir->status = $validated['status'];
         $laporanAkhir->save(); // Save the updated status
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Berhasil disimpan.');
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


public function report(Request $request, $jenis)
    {
        $user = Auth::user(); // Ambil data user yang sedang login

        // Ambil filter rentang tanggal dari request
        $startDate = $request->input('start_date'); // Tanggal mulai
        $endDate = $request->input('end_date'); // Tanggal akhir

        // Mengubah format tanggal jika ada filter
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        // Jika user adalah Kepala LPPM, ambil semua laporan akhir yang disetujui (approved)
        if ($user->hasRole('Kepala LPPM')) {
            $laporanAkhir = LaporanAkhir::where('status', 'approved') // Hanya laporan dengan status approved
                ->when($jenis, function ($query, $jenis) {
                    $query->where('jenis', $jenis);
                })
                ->when($startDate, function ($query) use ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                })
                ->when($endDate, function ($query) use ($endDate) {
                    $query->where('created_at', '<=', $endDate);
                })
                ->get();

            // Return data ke view
            return view('laporan_akhir.report', compact('laporanAkhir', 'jenis', 'startDate', 'endDate'));

        } elseif ($user->hasRole('Dosen')) {
            // Untuk Dosen, ambil usulan yang terkait dengan ketua dosen dan jenis skema
            $usulans = Usulan::where('ketua_dosen_id', $user->dosen->id)
                ->where('jenis_skema', $jenis)
                ->get();

            $laporanAkhir = collect(); // Menampung laporan akhir yang ditemukan untuk dosen terkait
            
            foreach ($usulans as $usulan) {
                // Cari laporan akhir yang terkait dengan usulan
                $existingLaporan = LaporanAkhir::where('usulan_id', $usulan->id)
                    ->where('status', 'approved') // Hanya laporan dengan status approved
                    ->when($startDate, function ($query) use ($startDate) {
                        $query->where('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function ($query) use ($endDate) {
                        $query->where('created_at', '<=', $endDate);
                    })
                    ->first();
                
                if ($existingLaporan) {
                    // Jika ditemukan laporan akhir yang approved, tambahkan ke koleksi
                    $laporanAkhir->push($existingLaporan);
                }
            }

            // Return data ke view untuk Dosen
            return view('laporan_akhir.report', compact('laporanAkhir', 'jenis', 'startDate', 'endDate'));
        }

        // Jika user tidak memiliki role yang diizinkan
        return redirect()->route('home')->with('error', 'Akses ditolak');
    }

    public function export(Request $request, $jenis)
    {
        // Pass 'jenis' to the export class
        return Excel::download(new LaporanAkhirExport($jenis), 'laporan_akhir_' . $jenis . '.xlsx');
    }

    public function filterOrExport(Request $request)
    {
        $startYear = $request->input('startYear');
        $endYear = $request->input('endYear');
        $action = $request->input('action'); // 'filter' or 'export'

        // Validasi input
        $request->validate([
            'startYear' => 'required|numeric|min:2025|max:2030',
            'endYear' => 'required|numeric|min:2025|max:2030|gte:startYear',
        ]);

        // Ambil data berdasarkan filter tahun
        $laporanAkhir = LaporanAkhir::whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $endYear)
            ->get();

        if ($action === 'filter') {
            // Kembalikan data ke view untuk ditampilkan
            return view('report.index', compact('laporanAkhir'));
        } elseif ($action === 'export') {
            // Ekspor data ke Excel
            return Excel::download(new ReportExport($laporanAkhir), "laporan_{$startYear}_{$endYear}.xlsx");
        }
    }
}
