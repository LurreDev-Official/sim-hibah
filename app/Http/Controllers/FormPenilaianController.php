<?php

namespace App\Http\Controllers;

use App\Models\Usulan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanAkhir;

use App\Models\FormPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\UsulanPerbaikan;
use App\Models\KriteriaPenilaian;
use App\Models\PenilaianReviewer;
use Illuminate\Http\Request;
use App\Models\Reviewer;
use Illuminate\Support\Facades\Validator;


use Illuminate\Support\Facades\DB;

class FormPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formPenilaians = FormPenilaian::all(); // Mengambil semua form penilaian
        return view('form_penilaian.index', compact('formPenilaians')); // Mengembalikan view dengan data
    }

     /**
     * Display the form for creating a new Usulan Penilaian.
     */
    public function createUsulan($usulanId)
    {
        // Retrieve the Usulan by ID
        $usulan = Usulan::findOrFail($usulanId);

        // Retrieve relevant IndikatorPenilaian based on Usulan's criteria
        $indikatorPenilaians = $this->getFilteredIndikators($usulan);

        // Return the view for Usulan form
        return view('form_penilaian.create_usulan', compact('usulan', 'indikatorPenilaians'));
    }

    /**
     * Display the form for creating a new Laporan Kemajuan.
     */
     
     public function createLaporanKemajuan($id)
{
    // Cek jika PenilaianReviewer dengan status 'sudah dinilai' atau 'sudah diperbaiki'
    $penilaianReviewer = PenilaianReviewer::where('laporankemajuan_id', $id)
        ->whereIn('status_penilaian', ['sudah dinilai', 'sudah diperbaiki'])
        ->first();

    if ($penilaianReviewer) {
        $message = $penilaianReviewer->status_penilaian == 'sudah dinilai' ? 
                    'Penilaian untuk laporan kemajuan ini sudah dinilai' :
                    'Penilaian untuk laporan kemajuan ini sudah diperbaiki';
                    
        return redirect()->route('laporan-kemajuan.index')->with('error', $message);
    }

    // Ambil data LaporanKemajuan berdasarkan ID
    $laporanKemajuan = LaporanKemajuan::findOrFail($id);

    // Retrieve KriteriaPenilaian yang sesuai dengan jenis dan proses 'Laporan Kemajuan'
    $matchingKriteria = KriteriaPenilaian::where('jenis', $laporanKemajuan->jenis)
                                          ->where('proses', 'Laporan Kemajuan')
                                          ->pluck('id');

    // Retrieve IndikatorPenilaian berdasarkan KriteriaPenilaian yang cocok
    $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
                                             ->whereIn('kriteria_id', $matchingKriteria)
                                             ->get();

    // Ambil data reviewer yang sedang login dan PenilaianReviewer terkait
    $reviewer = Reviewer::where('user_id', auth()->id())->first();
    $penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
                                          ->where('laporankemajuan_id', $id)
                                          ->firstOrFail();

    // Return view dengan data yang diperlukan
    return view('form_penilaian.create_laporan_kemajuan', compact('laporanKemajuan', 'penilaianReviewer', 'indikatorPenilaians'));
}

    

    /**
     * Display the form for creating a new Laporan Akhir.
     */
    public function createLaporanAkhir($id)
    {
        // Cek jika PenilaianReviewer dengan status 'sudah dinilai' atau 'sudah diperbaiki'
    $penilaianReviewer = PenilaianReviewer::where('laporanakhir_id', $id)
    ->whereIn('status_penilaian', ['sudah dinilai', 'sudah diperbaiki'])
    ->first();

if ($penilaianReviewer) {
    $message = $penilaianReviewer->status_penilaian == 'sudah dinilai' ? 
                'Penilaian untuk laporan Akhir ini sudah dinilai' :
                'Penilaian untuk laporan Akhir ini sudah diperbaiki';
                
    return redirect()->route('laporan-kemajuan.index')->with('error', $message);
}

// Ambil data LaporanAkhir berdasarkan ID
$laporanAkhir = LaporanAkhir::findOrFail($id);

// Retrieve KriteriaPenilaian yang sesuai dengan jenis dan proses 'Laporan Akhir'
$matchingKriteria = KriteriaPenilaian::where('jenis', $laporanAkhir->jenis)
                                      ->where('proses', 'Laporan Akhir')
                                      ->pluck('id');

// Retrieve IndikatorPenilaian berdasarkan KriteriaPenilaian yang cocok
$indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
                                         ->whereIn('kriteria_id', $matchingKriteria)
                                         ->get();

// Ambil data reviewer yang sedang login dan PenilaianReviewer terkait
$reviewer = Reviewer::where('user_id', auth()->id())->first();
$penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
                                      ->where('laporanakhir_id', $id)
                                      ->firstOrFail();

// Return view dengan data yang diperlukan
return view('form_penilaian.create_laporan_akhir', compact('laporanAkhir', 'penilaianReviewer', 'indikatorPenilaians'));
    }

    /**
     * Helper function to filter IndikatorPenilaian based on Usulan's criteria.
     */
    private function getFilteredIndikators($usulan)
    {
        // Get the 'jenis' and 'tipe' from the Usulan
        $jenis = $usulan->jenis_skema; // Assuming 'jenis_skema' matches with 'jenis' in KriteriaPenilaian
        $tipe = $usulan->rumpun_ilmu;  // Assuming 'rumpun_ilmu' matches with 'tipe' in KriteriaPenilaian

        // Retrieve all KriteriaPenilaian that match the 'jenis' and 'tipe' from the Usulan
        $matchingKriteria = KriteriaPenilaian::where('jenis', $jenis)
                                             ->where('tipe', $tipe)
                                             ->pluck('id');

        // Retrieve all IndikatorPenilaian based on matching KriteriaPenilaian IDs
        return IndikatorPenilaian::with('kriteriaPenilaian')
                                 ->whereIn('kriteria_id', $matchingKriteria)
                                 ->get();
    }

    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create($usulanId)
    {
        // Memeriksa apakah PenilaianReviewer dengan status 'sudah dinilai' ditemukan
        $penilaianReviewerDinilai = PenilaianReviewer::where('usulan_id', $usulanId)
            ->where('status_penilaian', 'sudah dinilai')
            ->first();
    
        // Memeriksa apakah PenilaianReviewer dengan status 'sudah diperbaiki' ditemukan
        $penilaianReviewerDiperbaiki = PenilaianReviewer::where('usulan_id', $usulanId)
            ->where('status_penilaian', 'sudah diperbaiki')
            ->first();
        
        // Jika sudah ada penilaian dengan status 'sudah dinilai'
        if ($penilaianReviewerDinilai) {
            return redirect()->route('review-usulan.index')->with('success', 'Penilaian untuk usulan ini sudah dinilai');
        }
    
        // Jika sudah ada penilaian dengan status 'sudah diperbaiki'
        if ($penilaianReviewerDiperbaiki) {
            return redirect()->route('review-usulan.index')->with('success', 'Penilaian untuk usulan ini sudah diperbaiki');
        }
    
        // Ambil data Usulan berdasarkan ID
        $usulan = Usulan::findOrFail($usulanId);
    
        // Ambil data reviewer yang sedang login
        $user = auth()->user(); 
        
        // Cari data reviewer berdasarkan user_id
        $reviewer = Reviewer::where('user_id', $user->id)->first();
    
        // Tentukan jenis skema dari usulan
        $jenis_skema = $usulan->jenis_skema;
    
        // Ambil KriteriaPenilaian yang sesuai dengan jenis dan proses dari Usulan
        $matchingKriteria = KriteriaPenilaian::where('jenis', $jenis_skema)
            ->where('proses', 'Usulan')
            ->pluck('id');
    
        // Ambil IndikatorPenilaian yang sesuai dengan KriteriaPenilaian yang telah difilter
        $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
            ->whereIn('kriteria_id', $matchingKriteria)
            ->get();
    
        // Cari PenilaianReviewer berdasarkan reviewer dan usulan
        $penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
            ->where('usulan_id', $usulanId)
            ->firstOrFail();
    
        // Tampilkan view form penilaian dengan data Usulan, IndikatorPenilaian, dan PenilaianReviewer
        return view('form_penilaian.create', compact('usulan', 'indikatorPenilaians', 'penilaianReviewer'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
  

     public function store(Request $request)
     {
         // Validasi data yang diterima
         $validator = Validator::make($request->all(), [
             'penilaian_reviewers_id' => 'required|exists:penilaian_reviewers,id',
             'indikator' => 'required|array', // Pastikan indikator adalah array
             'indikator.*.nilai' => 'required|integer|min:1|max:5', // Validasi jumlah bobot setiap indikator
             'indikator.*.catatan' => 'nullable|string|max:255', // Validasi catatan (opsional)
         ]);
     
         // Jika validasi gagal, kembalikan ke view dengan pesan error
         if ($validator->fails()) {
             return redirect()->back()
                 ->withErrors($validator) // Kirim error ke view
                 ->withInput(); // Kirim input yang sudah diisi kembali ke view
         }
     
         // Cek apakah penilaian untuk reviewer ini sudah ada
         $existingEntries = FormPenilaian::where('penilaian_reviewers_id', $request->penilaian_reviewers_id)->count();
         if ($existingEntries > 0) {
             return redirect()->back()->with('error', 'Penilaian untuk reviewer ini sudah pernah disubmit. Silakan gunakan fungsi edit.');
         }
     
         // Inisialisasi array untuk menghitung total nilai per kriteria
         $kriteriaTotals = [];
         $totalNilai = 0;
     
         // Mulai transaksi database untuk memastikan semua operasi berhasil atau gagal bersama
         DB::beginTransaction();
         
         try {
             // Iterasi setiap indikator untuk menghitung dan menyimpan data
             foreach ($request->indikator as $indikatorId => $data) {
                 // Periksa apakah indikator ditemukan
                 $indikator = IndikatorPenilaian::find($indikatorId);
     
                 if (!$indikator) {
                     // Jika indikator tidak ditemukan, kembalikan error
                     DB::rollBack();
                     return redirect()->back()->with('error', "Indikator dengan ID $indikatorId tidak ditemukan.");
                 }
     
                 // Tambahkan jumlah bobot ke total nilai untuk kriteria terkait
                 if (!isset($kriteriaTotals[$indikator->kriteria_id])) {
                     $kriteriaTotals[$indikator->kriteria_id] = 0;
                 }
                 $kriteriaTotals[$indikator->kriteria_id] += $data['nilai'];
     
                 // Cek apakah penilaian untuk indikator ini sudah ada
                 $existingForm = FormPenilaian::where('penilaian_reviewers_id', $request->penilaian_reviewers_id)
                     ->where('id_indikator', $indikator->id)
                     ->first();
     
                 if (!$existingForm) {
                     // Simpan data indikator ke dalam tabel FormPenilaian jika belum ada
                     FormPenilaian::create([
                         'penilaian_reviewers_id' => $request->penilaian_reviewers_id,
                         'id_kriteria' => $indikator->kriteria_id,
                         'id_indikator' => $indikator->id,
                         'catatan' => $data['catatan'] ?? null,
                         'nilai' => $data['nilai'], // Menyimpan nilai per indikator
                         'status' => 'sudah dinilai',
                     ]);
                 }
     
                 // Tambahkan nilai indikator ke total nilai keseluruhan
                 $totalNilai += $data['nilai'];
             }
     
             // Cek apakah PenilaianReviewer ditemukan
             $penilaianReviewer = PenilaianReviewer::findOrFail($request->penilaian_reviewers_id);
             
             // Cek apakah usulan perbaikan sudah ada
             $existingUsulan = UsulanPerbaikan::where('penilaian_id', $penilaianReviewer->id)->first();
     
             // Update status dan total nilai di tabel PenilaianReviewer
             $penilaianReviewer->update([
                 'status_penilaian' => 'sudah dinilai',
                 'proses_penilaian' => 'Usulan',
                 'urutan_penilaian' => 1,
                 'total_nilai' => $totalNilai,
             ]);
     
             // Tambahkan usulan perbaikan jika belum ada
             if (!$existingUsulan) {
                 UsulanPerbaikan::create([
                     'usulan_id' => $penilaianReviewer->usulan_id,
                     'status' => 'revisi',
                     'penilaian_id' => $penilaianReviewer->id,
                 ]);
     
                 $usulan = Usulan::findOrFail($penilaianReviewer->usulan_id);
                 $usulan->update(['status' => 'revision']);
             }
     
             // Commit transaksi jika semua operasi berhasil
             DB::commit();
     
             // Kembalikan respons sukses ke view
             return redirect()->back()->with('success', 'Form penilaian berhasil diperbarui.');
         } catch (\Exception $e) {
             // Rollback transaksi jika terjadi error
             DB::rollBack();
             return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
         }
     }
     



public function storeLaporanKemajuan(Request $request)
{
    // Validasi data yang diterima
    $validator = Validator::make($request->all(), [
        'penilaian_reviewers_id' => 'required|exists:penilaian_reviewers,id',
        'indikator' => 'required|array', // Pastikan indikator adalah array
        'indikator.*.nilai' => 'required|integer|min:1|max:5', // Validasi jumlah bobot setiap indikator
        'indikator.*.catatan' => 'nullable|string|max:255', // Validasi catatan (opsional)
    ]);

    // Jika validasi gagal, kembalikan ke view dengan pesan error
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator) // Kirim error ke view
            ->withInput(); // Kirim input yang sudah diisi kembali ke view
    }

    // Inisialisasi array untuk menghitung total nilai per kriteria
    $kriteriaTotals = [];
    $totalNilai = 0;

    // Iterasi setiap indikator untuk menghitung dan menyimpan data
    foreach ($request->indikator as $indikatorId => $data) {
        $indikator = IndikatorPenilaian::find($indikatorId);

        if ($indikator) {
            // Tambahkan jumlah bobot ke total nilai untuk kriteria terkait
            if (!isset($kriteriaTotals[$indikator->kriteria_id])) {
                $kriteriaTotals[$indikator->kriteria_id] = 0;
            }
            $kriteriaTotals[$indikator->kriteria_id] += $data['nilai'];

            // Simpan data indikator ke dalam tabel FormPenilaian
            FormPenilaian::create([
                'penilaian_reviewers_id' => $request->penilaian_reviewers_id,
                'id_kriteria' => $indikator->kriteria_id,
                'id_indikator' => $indikator->id,
                'catatan' => $data['catatan'] ?? null,
                'nilai' => $data['nilai'], // Menyimpan nilai per indikator
                'status' => 'sudah dinilai',
            ]);

            // Tambahkan nilai indikator ke total nilai keseluruhan
            $totalNilai += $data['nilai'];
        }
    }
 

    // Update status dan total nilai di tabel PenilaianReviewer
    $penilaianReviewer = PenilaianReviewer::findOrFail($request->penilaian_reviewers_id);
    $penilaianReviewer->update([
        'status_penilaian' => 'sudah dinilai',
     
        'total_nilai' => $totalNilai,
    ]);

  // Tambahkan usulan perbaikan jika diperlukan
  $laporanKemajuan = LaporanKemajuan::findOrFail($penilaianReviewer->laporankemajuan_id);
  if ($laporanKemajuan) {
      $laporanKemajuan->update(['status' => 'revision']);
  }
  // Redirect ke halaman laporan kemajuan dengan pesan sukses
  return redirect('review-laporan-kemajuan')->with('success', 'Penilaian Laporan Kemajuan berhasil disimpan!');
}

public function storeLaporanAkhir(Request $request)
{
    // Validasi data yang diterima
    $validator = Validator::make($request->all(), [
        'penilaian_reviewers_id' => 'required|exists:penilaian_reviewers,id',
        'indikator' => 'required|array', // Pastikan indikator adalah array
        'indikator.*.nilai' => 'required|integer|min:1|max:5', // Validasi jumlah bobot setiap indikator
        'indikator.*.catatan' => 'nullable|string|max:255', // Validasi catatan (opsional)
    ]);

    // Jika validasi gagal, kembalikan ke view dengan pesan error
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator) // Kirim error ke view
            ->withInput(); // Kirim input yang sudah diisi kembali ke view
    }

    // Inisialisasi array untuk menghitung total nilai per kriteria
    $kriteriaTotals = [];
    $totalNilai = 0;

    // Iterasi setiap indikator untuk menghitung dan menyimpan data
    foreach ($request->indikator as $indikatorId => $data) {
        $indikator = IndikatorPenilaian::find($indikatorId);

        if ($indikator) {
            // Tambahkan jumlah bobot ke total nilai untuk kriteria terkait
            if (!isset($kriteriaTotals[$indikator->kriteria_id])) {
                $kriteriaTotals[$indikator->kriteria_id] = 0;
            }
            $kriteriaTotals[$indikator->kriteria_id] += $data['nilai'];

            // Simpan data indikator ke dalam tabel FormPenilaian
            FormPenilaian::create([
                'penilaian_reviewers_id' => $request->penilaian_reviewers_id,
                'id_kriteria' => $indikator->kriteria_id,
                'id_indikator' => $indikator->id,
                'catatan' => $data['catatan'] ?? null,
                'nilai' => $data['nilai'], // Menyimpan nilai per indikator
                'status' => 'sudah dinilai',
            ]);

            // Tambahkan nilai indikator ke total nilai keseluruhan
            $totalNilai += $data['nilai'];
        }
    }
 

    // Update status dan total nilai di tabel PenilaianReviewer
    $penilaianReviewer = PenilaianReviewer::findOrFail($request->penilaian_reviewers_id);
    $penilaianReviewer->update([
        'status_penilaian' => 'sudah dinilai',
     
        'total_nilai' => $totalNilai,
    ]);

  // Tambahkan usulan perbaikan jika diperlukan
  $laporanAkhir = LaporanAkhir::findOrFail($penilaianReviewer->laporanakhir_id);
  if ($laporanAkhir) {
      $laporanAkhir->update(['status' => 'revision']);
  }

    // Redirect ke halaman laporan akhir dengan pesan sukses
    return redirect('review-laporan-akhir')->with('success', 'Penilaian Laporan Akhir berhasil disimpan!');
}



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $formPenilaian = FormPenilaian::findOrFail($id); // Mencari form penilaian berdasarkan ID
        return view('form_penilaian.show', compact('formPenilaian')); // Mengembalikan view untuk menampilkan detail
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $formPenilaian = FormPenilaian::findOrFail($id); // Mencari form penilaian berdasarkan ID
        return view('form_penilaian.edit', compact('formPenilaian')); // Mengembalikan view untuk mengedit form
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kriteria' => 'required|exists:kriteria_penilaians,id',
            'catatan' => 'nullable|string',
            'status' => 'required|string',
            'total_semua_kriteria' => 'nullable|numeric',
        ]);

        $formPenilaian = FormPenilaian::findOrFail($id); // Mencari form penilaian berdasarkan ID
        $formPenilaian->update($request->all()); // Memperbarui data form penilaian

        return redirect()->route('form-penilaian.index')->with('success', 'Form penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $formPenilaian = FormPenilaian::findOrFail($id); // Mencari form penilaian berdasarkan ID
        $formPenilaian->delete(); // Menghapus data form penilaian

        return redirect()->route('form-penilaian.index')->with('success', 'Form penilaian berhasil dihapus.');
    }

    public function perbaikan($usulan_id)
    {
        $user = auth()->user(); // Ambil data user yang sedang login

       // Ambil data reviewer berdasarkan user_id
        $reviewer = Reviewer::where('user_id', $user->id)->first();

        // Ambil data penilaian berdasarkan reviewer_id dan usulan_id
        $penilaianReviewers = PenilaianReviewer::with('usulan') 
            ->where('reviewer_id', $reviewer->id)
            ->where('id', $usulan_id)
            ->firstOrFail(); // Gunakan firstOrFail jika ingin menangani exception
        // Untuk setiap penilaian, ambil data perbaikan terkait melalui relasi
        $usulanPerbaikans = PenilaianReviewer::with('usulanPerbaikan')
            ->where('usulan_id', $penilaianReviewers->usulan_id)
            ->get()
            ->pluck('usulanPerbaikan') // Mengambil hanya data perbaikan yang terkait
            ->flatten(); // Menggabungkan array jika ada lebih dari satu perbaikan
    
        // Mengirim data ke view
        return view('perbaikan_penilaian.show', compact('usulanPerbaikans', 'penilaianReviewers'));
    }





   

}
