<?php

namespace App\Http\Controllers;

use App\Models\Usulan;
use App\Models\FormPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\UsulanPerbaikan;
use App\Models\KriteriaPenilaian;
use App\Models\PenilaianReviewer;
use Illuminate\Http\Request;
use App\Models\Reviewer;
use Illuminate\Support\Facades\Validator;

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
    public function createLaporanKemajuan($usulanId)
    {
        // Retrieve the Usulan by ID
        $usulan = Usulan::findOrFail($usulanId);

        // Retrieve relevant IndikatorPenilaian for Laporan Kemajuan
        $indikatorPenilaians = $this->getFilteredIndikators($usulan);

        // Return the view for Laporan Kemajuan form
        return view('form_penilaian.create_laporan_kemajuan', compact('usulan', 'indikatorPenilaians'));
    }

    /**
     * Display the form for creating a new Laporan Akhir.
     */
    public function createLaporanAkhir($usulanId)
    {
        // Retrieve the Usulan by ID
        $usulan = Usulan::findOrFail($usulanId);

        // Retrieve relevant IndikatorPenilaian for Laporan Akhir
        $indikatorPenilaians = $this->getFilteredIndikators($usulan);

        // Return the view for Laporan Akhir form
        return view('form_penilaian.create_laporan_akhir', compact('usulan', 'indikatorPenilaians'));
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

        $penilaianReviewerDinilai = PenilaianReviewer::where('usulan_id', $usulanId)
        ->where('status_penilaian', 'sudah dinilai')
        ->first();
    
    $penilaianReviewerDiperbaiki = PenilaianReviewer::where('usulan_id', $usulanId)
        ->where('status_penilaian', 'sudah diperbaiki')
        ->first();
    
    // Memeriksa apakah PenilaianReviewer dengan status 'sudah dinilai' ditemukan
    if ($penilaianReviewerDinilai) {
        return redirect()->route('penilaian-usulan.index')->with('error', 'Penilaian untuk usulan ini sudah dinilai');
    }
    
    // Memeriksa apakah PenilaianReviewer dengan status 'sudah diperbaiki' ditemukan
    if ($penilaianReviewerDiperbaiki) {
        return redirect()->route('penilaian-usulan.index')->with('error', 'Penilaian untuk usulan ini sudah diperbaiki');
    }
    
else {
            # code...
        
        // Retrieve the Usulan by ID
        $usulan = Usulan::findOrFail($usulanId);
        $user = auth()->user(); // Ambil data user yang sedang login
    
        $reviewer = Reviewer::where('user_id', $user->id)->first();
        
        $jenis_skema = $usulan->jenis_skema;  
        // Retrieve all KriteriaPenilaian that match the 'jenis' and 'tipe' from the Usulan
        $matchingKriteria = KriteriaPenilaian::where('jenis', $jenis_skema)
                                             ->where('proses', 'usulan')
                                             ->pluck('id');
                                            //  dd($matchingKriteria);
        // Retrieve all IndikatorPenilaian based on matching KriteriaPenilaian IDs
        $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
                                                  ->whereIn('kriteria_id', $matchingKriteria)
                                                  ->get();

        $penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
                                                  ->where('usulan_id', $usulanId)
                                                  ->firstOrFail();

        // Return the view for creating Form Penilaian with Usulan and filtered IndikatorPenilaian data
        return view('form_penilaian.create', compact('usulan', 'indikatorPenilaians','penilaianReviewer'));

    }

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
            'indikator.*.jumlah_bobot' => 'required|integer|min:1|max:5', // Validasi setiap jumlah bobot
        ]);

        // Jika validasi gagal, kembalikan respons error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity
        }

        // Hitung subtotal dari bobot yang dipilih
        $subTotal = 0;

        // Untuk menyimpan id_kriteria yang unik
        $kriteriaIds = [];

        foreach ($request->indikator as $indikatorId => $data) {
            $subTotal += $data['jumlah_bobot']; // Menjumlahkan bobot yang dipilih

            // Ambil kriteria id dari indikator
            $indikator = IndikatorPenilaian::find($indikatorId);
            if ($indikator) {
                $kriteriaIds[] = $indikator->kriteria_id; // Menyimpan kriteria id
            }
        }

        // Mengambil kriteria id yang unik
        $uniqueKriteriaIds = array_unique($kriteriaIds);
      // Inisialisasi total_nilai
      $total_nilai = 0;
        // Simpan data ke dalam database untuk setiap kriteria
        foreach ($uniqueKriteriaIds as $kriteriaId) {
            $formPenilaian = FormPenilaian::create([
                'penilaian_reviewers_id' => $request->penilaian_reviewers_id,
                'id_kriteria' => $kriteriaId, // Simpan id_kriteria
                'catatan' => $request->catatan ?? null, // Jika ada catatan
                'sub_total' => $subTotal, // Simpan subtotal yang telah dihitung
                'status' => 'sudah dinilai', // Status default jika tidak ada
            ]);
           // Tambahkan sub_total ke total_nilai
           $total_nilai += $subTotal;
        }

        // Menggunakan findOrFail untuk menemukan berdasarkan ID
        $penilaianReviewer = PenilaianReviewer::findOrFail($request->penilaian_reviewers_id);
        // Memperbarui status_penilaian dan total_nilai
        $penilaianReviewer->update([
            'status_penilaian' => 'sudah dinilai',
            'total_nilai' => $total_nilai,
        ]);

        if ($penilaianReviewer) {
            // Membuat instance baru dari UsulanPerbaikan
            $usulanPerbaikan = new UsulanPerbaikan();
            $usulanPerbaikan->usulan_id = $penilaianReviewer->usulan_id; // Usulan ID dari request
            $usulanPerbaikan->dokumen_usulan = '-'; // Dokumen Usulan dari request
            $usulanPerbaikan->status = 'revisi'; // Status perbaikan, misalnya 'revisi'
            $usulanPerbaikan->penilaian_id = $penilaianReviewer->id; // ID PenilaianReviewer yang terkait
            $usulanPerbaikan->save(); // Menyimpan data baru ke database
            $usulan = Usulan::findOrFail($penilaianReviewer->usulan_id);
            $usulan->status = 'revision';
            $usulan->save();


        }
        // Kembalikan respons sukses
        return redirect()->back()->with('success', 'Form penilaian berhasil diperbarui.');

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


    // FormPenilaianController.php
    public function updateStatus($id, Request $request)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|string|in:Diterima',  // Status hanya bisa 'Diterima'
        ]);
    
        // Cari PenilaianReviewer berdasarkan ID
        $penilaianReviewer = PenilaianReviewer::findOrFail($id);
    
        // Update status_penilaian sesuai dengan input dari form
        $penilaianReviewer->status_penilaian = $request->input('status');
        $penilaianReviewer->save();
    
        // Redirect kembali ke halaman penilaian-usulan dengan pesan sukses
        return redirect()->route('penilaian-usulan.index')
                         ->with('success', 'Status penilaian berhasil diperbarui.');
    }
    
    

}
