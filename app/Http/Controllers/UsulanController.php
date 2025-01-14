<?php

namespace App\Http\Controllers;

use App\Models\Usulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use App\Models\AnggotaMahasiswa;
use App\Models\Reviewer;
use App\Models\PenilaianReviewer;
use App\Models\FormPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\UsulanPerbaikan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use PDF;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use App\Models\TemplateDokumen;
use App\Exports\UsulanExport;
use Maatwebsite\Excel\Facades\Excel;

class UsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user(); // Ambil data user yang sedang login
    
        // Jika user memiliki role Kepala LPPM, tampilkan semua usulan
        if ($user->hasRole('Kepala LPPM')) {
            // Ambil semua usulan tanpa filter
            $usulans = Usulan::where('jenis_skema', $jenis)
            ->with('penilaianReviewers.reviewer') // Eager load penilaianReviewers and related reviewers
            ->paginate(10);

      // Check if all reviewers for each usulan have accepted (status == 'Diterima')
      foreach ($usulans as $usulan) {
        // Count the total number of reviewers
        $totalReviewers = $usulan->penilaianReviewers->count();

        // Count the number of reviewers who have accepted (status == 'Diterima')
        $acceptedReviewers = $usulan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();

        // If the total reviewers count matches the accepted reviewers count, set allReviewersAccepted to true
        $usulan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
    }
   // Fetch all reviewers (if you need to display them too)
   $reviewers = Reviewer::with('user')->get();
    
            return view('usulan.index', compact('usulans', 'jenis','reviewers'));
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
        $user_id = auth()->user()->id;
        $dosen = Dosen::where('user_id', $user_id)->first();
        $usulanKetua = Usulan::
        where('ketua_dosen_id','=', $dosen->id) // Ganti '1' dengan ID dosen yang sesuai
        ->where('tahun_pelaksanaan', Carbon::now()->year) // Pastikan tahun saat ini
        ->where('jenis_skema',$jenis) // Validasi berdasarkan jenis skema
        ->count(); // Hitung jumlah record yang sesuai

    // dd($usulanKetua);

        if ($usulanKetua >= 1) {
            // Menggunakan back dengan pesan error
            return back()->with('error', 'Anda hanya bisa menjadi ketua di 1 usulan proposal untuk jenis skema: ' . $jenis);

        }


        // Show the form for creating a new usulan
        $dosen = Dosen::where('user_id', auth()->user()->id)->first();
        $ketua_dosen_id = $dosen->id;
        $dosens = Dosen::where('id', '!=',$ketua_dosen_id)
        ->where('status', 'anggota') // Sesuaikan 'anggota' dengan status yang diinginkan
        ->get();

        //get template
        $getTemplate = TemplateDokumen::where('proses', 'Usulan')->where('skema', $jenis)->first();
        return view('usulan.create', compact('dosens','jenis','getTemplate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $jenis)
    {
        $validation = $this->validasiUsulanBaru($jenis);

        if ($validation instanceof \Illuminate\Http\RedirectResponse) {
            return $validation; // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan error
        }
    
        $dosen = Dosen::where('user_id', Auth::user()->id)->first();
        if ($dosen->sinta_score >= 200) {
            return back()->with('error', 'Skoring SINTA Anda kurang dari 200, tidak bisa menjadi ketua pengusul.');
        } else {

    
        // Validate the incoming request data
        $validated = $request->validate([
            'judul_usulan' => 'required|string|max:255',
            'tahun_pelaksanaan' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'dokumen_usulan' => 'required|file|mimes:pdf|max:2048', // Only allow PDF files up to 2MB
            'rumpun_ilmu' => 'required|string|max:255',
            'bidang_fokus' => 'required|string|max:255',
            'tema_penelitian' => 'required|string|max:255',
            'topik_penelitian' => 'required|string|max:255',
            'lama_kegiatan' => 'required|string|max:50',
            'status' => 'required',
        ]);

        // Handle file upload for dokumen_usulan
        if ($request->hasFile('dokumen_usulan')) {
            $filePath = $request->file('dokumen_usulan')->store('usulan_dokumen', 'public');
            $validated['dokumen_usulan'] = $filePath;
        }

        $dosen = Dosen::where('user_id', auth()->user()->id)->first();
        $ketua_dosen_id = $dosen->id;

        // Create new usulan
        $usulan = Usulan::create([
            'judul_usulan' => $validated['judul_usulan'],
            'jenis_skema' => $jenis,
            'tahun_pelaksanaan' => $validated['tahun_pelaksanaan'],
            'ketua_dosen_id' => $ketua_dosen_id,
            'dokumen_usulan' => $validated['dokumen_usulan'], // Jika dokumen adalah file, ini harus di-upload terlebih dahulu
            'rumpun_ilmu' => $validated['rumpun_ilmu'],
            'bidang_fokus' => $validated['bidang_fokus'],
            'tema_penelitian' => $validated['tema_penelitian'],
            'topik_penelitian' => $validated['topik_penelitian'],
            'lama_kegiatan' => $validated['lama_kegiatan'],
            'status' => $validated['status'] ?? 'draft', // Set default status to 'draft' jika tidak disediakan
        ]);

        // Setelah usulan berhasil dibuat, buat anggota dosen
        AnggotaDosen::create([
            'usulan_id' => $usulan->id, // Ambil ID usulan yang baru saja dibuat
            'dosen_id' => $ketua_dosen_id, // ID ketua dosen
            'status_anggota' => 'ketua', // Status untuk ketua dosen
            'status' => 'terima', // Status untuk ketua dosen
        ]);

        // $table->enum('status', [
        //     'draft',        // Dokumen masih dalam tahap penyusunan oleh mahasiswa
        //     'submitted',    // Dokumen telah diajukan oleh mahasiswa
        //     'review',       // Dokumen sedang dalam tahap review oleh pembimbing/dosen
        //     'revision',     // Dosen meminta revisi terhadap dokumen yang diajukan
        //     'approved',     // Dokumen telah disetujui/direkomendasikan oleh pembimbing
        //     'rejected',     // Dokumen ditolak oleh pembimbing atau pihak berwenang
        // ]);
         // Retrieve all usulans where jenis_skema matches the $jenis (penelitian or pengabdian)
         $usulans = Usulan::where('jenis_skema', $jenis)->get();
         $reviewers = Reviewer::with('user')->get();

         return view('usulan.index', compact('usulans', 'jenis','reviewers')) ->with('success', 'Usulan berhasil ditambah!');

    }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jenis, $id)
    {
        // Find the usulan by its ID and jenis_skema
        $usulan = Usulan::where('id', $id)->where('jenis_skema', $jenis)->firstOrFail();

        // Show the edit form
        return view('usulan.edit', compact('usulan', 'jenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $jenis, $id)
    {
        // Find the usulan by its ID and jenis_skema
        $usulan = Usulan::where('id', $id)->where('jenis_skema', $jenis)->firstOrFail();

        // Validate the incoming request data
        $validated = $request->validate([
            'judul_usulan' => 'required|string|max:255',
            'tahun_pelaksanaan' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'dokumen_usulan' => 'nullable|file|mimes:pdf|max:2048', // Only allow PDF files up to 2MB
            'rumpun_ilmu' => 'required|string|max:255',
            'bidang_fokus' => 'required|string|max:255',
            'tema_penelitian' => 'required|string|max:255',
            'topik_penelitian' => 'required|string|max:255',
            'lama_kegiatan' => 'required|string|max:50',
            'status' => 'required',
            
        ]);

        // Handle file upload if new dokumen_usulan is provided
        if ($request->hasFile('dokumen_usulan')) {
            // Delete the old file if exists
            if ($usulan->dokumen_usulan) {
                Storage::disk('public')->delete($usulan->dokumen_usulan);
            }

            // Upload the new file
            $filePath = $request->file('dokumen_usulan')->store('usulan_dokumen', 'public');
            $validated['dokumen_usulan'] = $filePath;
        } else {
            // If no new file, keep the old one
            $validated['dokumen_usulan'] = $usulan->dokumen_usulan;
        }

        // Update usulan
        $usulan->update($validated);
        return redirect()->back() 
            ->with('success', 'Usulan berhasil diperbarui!');
    }

    public function export($jenis)
    {
        // Return the export file as an Excel download
        return Excel::download(new UsulanExport($jenis), 'usulan_' . $jenis . '.xlsx');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jenis, $id)
    {
        try {
            // Cari usulan berdasarkan ID dan jenis_skema
            $usulan = Usulan::where('id', $id)->where('jenis_skema', $jenis)->firstOrFail();
    
            // Hapus file dokumen terkait usulan jika file tersebut ada
            if (!empty($usulan->dokumen_usulan) && Storage::disk('public')->exists($usulan->dokumen_usulan)) {
                Storage::disk('public')->delete($usulan->dokumen_usulan);
            }
    
            // Lakukan penghapusan permanen pada usulan
            $usulan->delete();  // Menghapus data secara permanen
    
            // Kembalikan respons JSON jika berhasil
            return response()->json(['success' => 'Usulan berhasil dihapus!']);
        } catch (ModelNotFoundException $e) {
            // Handle jika usulan tidak ditemukan
            return response()->json(['error' => 'Usulan tidak ditemukan!'], 404);
        } catch (\Exception $e) {
            // Handle error lain yang tidak terduga
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus usulan!'], 500);
        }
    }
    public function detail($jenis, $id)
    {
        // Ambil data usulan berdasarkan jenis dan ID
        $usulan = Usulan::with('ketuaDosen', 'anggotaDosen', 'anggotaMahasiswa')->findOrFail($id);

       // Ambil data dosen terkait user yang sedang login
$currentDosen = Dosen::where('user_id', auth()->id())->first();

// Ambil semua dosen kecuali yang sedang login
$dosens = Dosen::where('id', '!=', $currentDosen->id)->get();


        // Ambil data anggota dosen dan mahasiswa berdasarkan usulan
        $anggotaDosen = AnggotaDosen::where('usulan_id', $id)->with('dosen.user')->get();
        $anggotaMahasiswa = AnggotaMahasiswa::where('usulan_id', $id)->get();

        return view('usulan.show', compact('usulan', 'jenis','dosens', 'anggotaDosen', 'anggotaMahasiswa', 'currentDosen'));
    }

    public function show($jenis)
    {
        $user = auth()->user(); // Ambil data user yang sedang login
    
        // Jika user memiliki role Kepala LPPM, tampilkan semua usulan
        // Check if the user has the 'Kepala LPPM' role
    if ($user->hasRole('Kepala LPPM')) {
        // Get all Usulan for the specified 'jenis_skema' and paginate
        $usulans = Usulan::where('jenis_skema', $jenis)
            ->with('penilaianReviewers.reviewer') // Eager load penilaianReviewers and related reviewers
            ->paginate(10);

        // Check if all reviewers for each usulan have accepted (status == 'Diterima')
        foreach ($usulans as $usulan) {
            // Count the total number of reviewers
            $totalReviewers = $usulan->penilaianReviewers->count();

            // Count the number of reviewers who have accepted (status == 'Diterima')
            $acceptedReviewers = $usulan->penilaianReviewers->where('status_penilaian', 'Diterima')->count();

            // If the total reviewers count matches the accepted reviewers count, set allReviewersAccepted to true
            $usulan->allReviewersAccepted = $totalReviewers === $acceptedReviewers;
        }

        // Fetch all reviewers (if you need to display them too)
        $reviewers = Reviewer::with('user')->get();

        return view('usulan.index', compact('usulans', 'reviewers', 'jenis'));
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

    public function submit($jenis, $id)
    {
        // Temukan usulan berdasarkan ID
        $usulan = Usulan::findOrFail($id);
        // Cek apakah ada anggota dosen yang statusnya belum disetujui
        $anggotaDosenBelumDisetujui = AnggotaDosen::where('usulan_id', $usulan->id)
            ->where('status', '!=', 'terima')
            ->exists(); // Menggunakan exists() untuk cek apakah ada yang memenuhi kriteria
    
        if ($anggotaDosenBelumDisetujui) {
            return redirect()->back()->with('error', 'Tidak dapat mengajukan usulan. Pastikan semua anggota dosen telah menyetujui.');
        }
    
        // Cek apakah status usulan masih 'draft'
        if ($usulan->status == 'draft') {
            // Ubah status menjadi 'submit'
            $usulan->status = 'submitted';
            $usulan->save();
    
            return redirect()->back()->with('success', 'Usulan berhasil diajukan.');
        }
    
        return redirect()->back()->with('error', 'Usulan ini sudah diajukan sebelumnya.');
    }


public function kirim(Request $request)
{
    // Validasi permintaan
    $request->validate([
        'usulan_id' => 'required|integer',
        'reviewer_id' => 'required|array', // Reviwewer ID harus berupa array
        'reviewer_id.*' => 'required|integer', // Setiap reviewer_id harus berupa integer
        'action' => 'required|string|in:kirim,kirim_ulang', // Validasi action
    ]);

    // Ambil nilai input dari request
    $usulanId = $request->input('usulan_id');
    // dd($usulanId);
    $jenis = $request->input('jenis');
    $reviewerIds = $request->input('reviewer_id');
    $action = $request->input('action'); // Mendapatkan aksi tombol yang diklik

    // Temukan usulan berdasarkan ID
    $usulan = Usulan::findOrFail($usulanId);

    if ($action === 'kirim') {
        // Logika untuk "Kirim"
        if ($usulan->status === 'submitted') {
            $usulan->status = 'review';
            $usulan->save();

            // Loop untuk menambahkan reviewer baru
            $duplicateReviewers = [];
            foreach ($reviewerIds as $reviewerId) {
                $existingRecord = PenilaianReviewer::where('usulan_id', $usulanId)
                                                   ->where('reviewer_id', $reviewerId)
                                                   ->first();

                if ($existingRecord) {
                    $duplicateReviewers[] = $reviewerId;
                } else {
                    PenilaianReviewer::create([
                        'usulan_id' => $usulanId,
                        'status_penilaian' => 'Belum Dinilai',
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
        if ($usulan->status === 'review') {
            // Hapus reviewer sebelumnya
            PenilaianReviewer::where('usulan_id', $usulanId)->delete();

            // Tambahkan reviewer baru
            foreach ($reviewerIds as $reviewerId) {
                PenilaianReviewer::create([
                    'usulan_id' => $usulanId,
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
        // Fetch the usulan by ID
        $usulan = Usulan::findOrFail($id);

        // Fetch related penilaianReviewer and indikatorPenilaians
        $penilaianReviewer = PenilaianReviewer::where('usulan_id', $id)->firstOrFail();
        $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
            ->whereIn('kriteria_id', KriteriaPenilaian::where('jenis', $usulan->jenis_skema)->where('proses', 'Usulan')->pluck('id'))
            ->get();
// Fetch the related UsulanPerbaikan (if exists)
$usulanPerbaikan = UsulanPerbaikan::where('usulan_id', $id)
->where('penilaian_id', $penilaianReviewer->id)
->first(); // Fetch the existing UsulanPerbaikan record

// Return the perbaiki revisi view with the additional usulanPerbaikan variable
return view('usulan.perbaiki_revisi', compact('usulan', 'penilaianReviewer', 'indikatorPenilaians', 'usulanPerbaikan'));
    }

    public function simpanPerbaikan(Request $request, $id)
{
    // Validate input
    $request->validate([
        'file_perbaikan' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
    ]);
    
    // Fetch the related PenilaianReviewer
    $penilaianReviewer = PenilaianReviewer::where('usulan_id', $id)->firstOrFail();
    
    // Handle file upload
    $file = $request->file('file_perbaikan');
    
    // Get the original file name
    $originalFileName = $file->getClientOriginalName(); 
    
    // Define the storage path and store the file with its original name
    $filePath = $file->storeAs('usulan_perbaikans', $originalFileName, 'public'); // Store with original name
    
    // Fetch or create the UsulanPerbaikan record
    $usulanPerbaikan = UsulanPerbaikan::updateOrCreate(
        ['usulan_id' => $id, 'penilaian_id' => $penilaianReviewer->id], // Match these columns
        [
            'dokumen_usulan' => $filePath, // Store the path to the file
            'status' => 'sudah diperbaiki', // Update or set this status
        ]
    );

    // Update the status of Usulan to 'waiting approved'
    $usulan = Usulan::findOrFail($id);
    $usulan->status = 'waiting approved';
    $usulan->save();

    // Redirect back with success message
    return redirect()->back()->with('success', 'Berhasil di simpan.');
}




    public function updateStatus($id, Request $request)
{
    // Find the Usulan by ID
    $usulan = Usulan::findOrFail($id);

    // Validate the request data
    $validated = $request->validate([
        'status' => 'required|in:approved,rejected', // Only allow 'approved' or 'rejected' status
    ]);

    // Update the status of the Usulan
    $usulan->status = $validated['status'];
    $usulan->save(); // Save the updated status
    return redirect()->back()->with('success', 'Berhasil di simpan.');
}
 


public function cetakBuktiACC($id)
{
    // Ambil data usulan dengan relasi terkait
    $usulan = Usulan::with(['ketuaDosen', 'anggotaDosen.dosen.user', 'anggotaMahasiswa'])
                    ->findOrFail($id);

    // Ambil timestamp Unix
    $timestamp = time(); // Mendapatkan timestamp Unix saat ini

    // Generate Barcode 2D (QR Code) menggunakan milon/barcode
    // Menggabungkan ID usulan dan timestamp ke dalam QR Code
    $dataToEncode = [
        'id' => $usulan->id,
        'timestamp' => $timestamp,
    ];
    
    // Encode data menjadi JSON string
    $jsonData = json_encode($dataToEncode);

    // Generate QR Code dalam format PNG
    $barcode2D = \DNS2D::getBarcodePNG($jsonData, 'QRCODE'); // Menghasilkan barcode dalam format PNG

    // Simpan Barcode 2D ke file sementara di storage
    $barcode2DPath = 'public/images/ttd_usulan_2D_' . $usulan->id . '.png';
    \Storage::put($barcode2DPath, base64_decode($barcode2D)); // Decode base64 sebelum disimpan

    // Dapatkan URL untuk QR Code yang disimpan
    $barcode2DUrl = \Storage::url($barcode2DPath); // Mendapatkan URL yang dapat diakses publik

    // Mengonversi QR Code ke Base64
    $barcodeBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents(storage_path('app/' . $barcode2DPath)));

    // Generate PDF
    $pdf = \PDF::loadView('usulan.bukti_acc', [
        'usulan' => $usulan,
        'barcodeBase64' => $barcodeBase64, // Kirim Base64 barcode ke view
    ]);

    // Kembalikan file PDF untuk diunduh
    // return $pdf->download('bukti_acc_' . $usulan->id . '.pdf');

    return view('usulan.printpengasahan', compact('usulan', 'barcodeBase64'));
}


public function validasiUsulanBaru($jenis)
{
    $user_id = auth()->user()->id;
    $dosen = Dosen::where('user_id', $user_id)->first();

    $usulanKetua = Usulan::where('ketua_dosen_id', $dosen->id)
        ->where('tahun_pelaksanaan', Carbon::now()->year)
        ->where('jenis_skema', $jenis)
        ->count();

    if ($usulanKetua >= 1) {
        return back()->with('error', 'Anda sudah menjadi ketua di 1 usulan proposal pada tahun ini.');
    }

    $usulanAnggota = AnggotaDosen::where('dosen_id', $dosen->id)
        ->where('status_anggota', 'anggota')
        ->whereHas('proposal', function($query) {
            $query->whereYear('tahun_pelaksanaan', Carbon::now()->year);
        })
        ->count();

    if ($usulanAnggota >= 1) {
        return back()->with('error', 'Anda sudah menjadi anggota di 1 usulan proposal pada skema '.$jenis);
    }
}




}
