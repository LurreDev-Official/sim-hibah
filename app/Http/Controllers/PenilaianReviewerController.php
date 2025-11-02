<?php

namespace App\Http\Controllers;

use App\Models\PenilaianReviewer;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use App\Models\AnggotaMahasiswa;
use App\Models\Reviewer;
use App\Models\Usulan;
use App\Models\FormPenilaian;
use App\Models\KriteriaPenilaian;
use App\Models\IndikatorPenilaian;
use App\Models\UsulanPerbaikan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanAkhir;


use Illuminate\Http\Request;
class PenilaianReviewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user(); // Ambil data user yang sedang login
        $penilaianReviewers = [];
    
        // Jika user memiliki role Kepala LPPM
        if ($user->hasRole('Kepala LPPM')) {
            // Kepala LPPM dapat melihat semua penilaian reviewer
            $penilaianReviewers = PenilaianReviewer::with('usulan')->paginate(10);
        }
        // Jika user memiliki role Reviewer
        elseif ($user->hasRole('Reviewer')) {
            // Reviewer hanya dapat melihat penilaian yang terkait dengan dirinya
            $reviewer = Reviewer::where('user_id', $user->id)->first();
    
            if ($reviewer) {
                $penilaianReviewers = PenilaianReviewer::with('usulan')
                    ->where('reviewer_id', $reviewer->id)
                    ->paginate(10);
            } else {
                return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
            }
           return view('penilaian_reviewers.index', compact('penilaianReviewers'));

        }
        // Jika user memiliki role Dosen
        elseif ($user->hasRole('Dosen')) {
            // Dosen tidak memiliki akses langsung ke penilaian reviewer
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data penilaian reviewer.');
        } else {
            // Jika role tidak dikenali atau user tidak memiliki role yang valid
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return the view to create a new PenilaianReviewer
        // return view('penilaian_reviewers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data for FormPenilaian
        $formValidated = $request->validate([
            'nama' => 'required|string|max:255',
            'id_kriteria' => 'nullable|integer|exists:kriteria_penilaians,id',
            'catatan' => 'nullable|string',
            'status' => 'required|string|max:255',
            'total_semua_kriteria' => 'nullable|numeric|min:0',
        ]);

        // Save the FormPenilaian data
        $formPenilaian = FormPenilaian::create($formValidated);

        // Get the ID of the saved FormPenilaian
        $formPenilaianId = $formPenilaian->id;

        // Validate incoming request data for PenilaianReviewer
        $reviewerValidated = $request->validate([
            'usulan_id' => 'required|integer|exists:usulans,id',
            'status_penilaian' => 'required|string|max:255',
            'reviewer_id' => 'required|integer|exists:reviewers,id',
        ]);

        // Add form_penilaian_id to the validated data
        $reviewerValidated['form_penilaian_id'] = $formPenilaianId;

        // Save the data to the penilaian_reviewers table
        PenilaianReviewer::create($reviewerValidated);

        // Redirect back to the index page with a success message
        return redirect()->route('penilaian_reviewers.index')->with('success', 'Penilaian Reviewer created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\View\View
     */
    public function show(PenilaianReviewer $penilaianReviewer)
    {
        // Return the view to display a single PenilaianReviewer
        // return view('penilaian_reviewers.show', compact('penilaianReviewer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\View\View
     */
    public function edit(PenilaianReviewer $penilaianReviewer)
    {
        // Return the view to edit a PenilaianReviewer
        // return view('penilaian_reviewers.edit', compact('penilaianReviewer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, PenilaianReviewer $penilaianReviewer)
    {
         // Validate incoming request data for FormPenilaian
         $formValidated = $request->validate([
            'nama' => 'required|string|max:255',
            'id_kriteria' => 'nullable|integer|exists:kriteria_penilaians,id',
            'catatan' => 'nullable|string',
            'status' => 'required|string|max:255',
            'total_semua_kriteria' => 'nullable|numeric|min:0',
        ]);

        // Save the FormPenilaian data
        $formPenilaian = FormPenilaian::create($formValidated);

        // Get the ID of the saved FormPenilaian
        $formPenilaianId = $formPenilaian->id;

        // Validate incoming request data for PenilaianReviewer
        $reviewerValidated = $request->validate([
            'usulan_id' => 'required|integer|exists:usulans,id',
            'status_penilaian' => 'required|string|max:255',
            'reviewer_id' => 'required|integer|exists:reviewers,id',
        ]);

        // Add form_penilaian_id to the validated data
        $reviewerValidated['form_penilaian_id'] = $formPenilaianId;

        // Update the existing PenilaianReviewer record
        $penilaianReviewer->update($reviewerValidated);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PenilaianReviewer  $penilaianReviewer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PenilaianReviewer $penilaianReviewer)
    {
        // Delete the PenilaianReviewer record
        $penilaianReviewer->delete();

        // Redirect back to the index page with a success message
        return redirect()->route('penilaian_reviewers.index')->with('success', 'Penilaian Reviewer deleted successfully.');
    }

    /**
     * Display PenilaianReviewer by reviewer_id and status.
     *
     * @param  int  $reviewer_id
     * @param  string  $status
     * @return \Illuminate\View\View
     */
    public function getByReviewerAndStatus($reviewer_id, $status)
    {
        // Fetch data based on reviewer_id and status_penilaian
        $penilaianReviewers = PenilaianReviewer::where('reviewer_id', $reviewer_id)
            ->where('status_penilaian', $status)
            ->with('usulan') // Assuming the relation to Usulan is needed
            ->get();

        // Return to a Blade view with the fetched data
        return view('penilaian_reviewers.index', compact('penilaianReviewers'));
    }



    
    public function indexPenilaianUsulan()
{
    $reviewer = Reviewer::where('user_id', auth()->id())->first();

    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }

    $usulans = PenilaianReviewer::where('reviewer_id', $reviewer->id)
        ->where('status_penilaian', 'sudah dinilai')
        ->with('usulan')
        ->get();
    
       // Ambil semua UsulanPerbaikan yang terkait dengan usulan_id yang ditemukan
       $usulanPerbaikans = UsulanPerbaikan::all();

    return view('penilaian_reviewers.review_usulan', compact('usulans','usulanPerbaikans'));
}


    /**
 * Menampilkan daftar usulan untuk direview.
 *
 * @return \Illuminate\View\View
 */
public function indexReviewUsulan()
{
    // Get the reviewer for the authenticated user
    $reviewer = Reviewer::where('user_id', auth()->id())->first();
    // Check if the reviewer exists
    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }
    // Fetch the usulans that have been reviewed by the current reviewer
    $getpenilaianreview = PenilaianReviewer::with('usulan')->where('reviewer_id', $reviewer->id)
    ->where('proses_penilaian', 'Usulan')
    ->where('urutan_penilaian', 1)
    ->where(function($query) {
        // Filter berdasarkan apakah salah satu id (usulan, laporan kemajuan, laporan akhir) tidak null
        $query->whereNotNull('usulan_id');
    })
        ->with('usulan') // Eager load 'usulan' relationship
        ->get();
    $usulanPerbaikans = UsulanPerbaikan::whereIn('usulan_id', $getpenilaianreview->pluck('usulan_id'))->get();
    return view('penilaian_reviewers.review_usulan', compact('getpenilaianreview', 'usulanPerbaikans'));
}


/**
 * Menampilkan daftar laporan kemajuan untuk direview.
 *
 * @return \Illuminate\View\View
 */


 public function indexReviewLaporanKemajuan()
{
    // Get the reviewer for the authenticated user
    $reviewer = Reviewer::where('user_id', auth()->id())->first();
    
    // Check if the reviewer exists
    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }

    // Fetch the penilaian reviewer with conditions
    $getpenilaianreview = PenilaianReviewer::where('reviewer_id', $reviewer->id)
        ->where('proses_penilaian', 'Laporan Kemajuan')
        ->where('urutan_penilaian', 2)
        ->where(function($query) {
            $query->whereNotNull('laporankemajuan_id');
        })
        ->with('laporankemajuan') // Eager load 'usulan' relationship
        ->get();

    // Fetch related LaporanKemajuan for each review
    // This will loop through each 'getpenilaianreview' item and fetch the corresponding 'laporan kemajuan'
    $laporanKemajuans = $getpenilaianreview->map(function ($penilaian) {
        return LaporanKemajuan::findOrFail($penilaian->laporankemajuan_id);
    });

    return view('penilaian_reviewers.review_laporan_kemajuan', compact('getpenilaianreview', 'laporanKemajuans'));
}




/**
 * Menampilkan daftar laporan akhir untuk direview.
 *
 * @return \Illuminate\View\View
 */
public function indexReviewLaporanAkhir()
{
    // Get the reviewer for the authenticated user
    $reviewer = Reviewer::where('user_id', auth()->id())->first();
    
    // Check if the reviewer exists
    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }

    // Fetch the penilaian reviewer with conditions
    $getpenilaianreview = PenilaianReviewer::where('reviewer_id', $reviewer->id)
        ->where('proses_penilaian', 'Laporan Akhir')
        ->where('urutan_penilaian', 3)
        ->where(function($query) {
            $query->whereNotNull('laporanakhir_id');
        })
        ->with('laporanakhir') // Eager load 'usulan' relationship
        ->get();

    // This will loop through each 'getpenilaianreview' item and fetch the corresponding 'laporan kemajuan'
    $laporanAkhirs = $getpenilaianreview->map(function ($penilaian) {
        return LaporanAkhir::findOrFail($penilaian->laporanakhir_id);
    });

    return view('penilaian_reviewers.review_laporan_akhir', compact('getpenilaianreview', 'laporanAkhirs'));
}


public function lihatReviewUsulan($usulanId)
{
    // Ambil data usulan berdasarkan ID
    $usulan = Usulan::findOrFail($usulanId);

    // Ambil data user yang sedang login
    $user = auth()->user();

    // Cari reviewer berdasarkan user login
    $reviewer = Reviewer::where('user_id', $user->id)->first();

    // Ambil jenis skema dari usulan
    $jenis_skema = $usulan->jenis_skema;

    // Ambil KriteriaPenilaian yang sesuai dengan jenis dan proses 'usulan'
    $matchingKriteria = KriteriaPenilaian::where('jenis', $jenis_skema)
                                         ->where('proses', 'usulan')
                                         ->pluck('id');

    // Ambil semua IndikatorPenilaian berdasarkan KriteriaPenilaian yang sesuai
    $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
                                              ->whereIn('kriteria_id', $matchingKriteria)
                                              ->get();

    // Ambil data penilaian reviewer
   // Ambil data penilaian reviewer dengan kondisi where pada relasi
    $penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
    ->where('usulan_id', $usulanId)

    ->with(['formPenilaians' => function ($query) {
        $query->with('indikator');
    }])
    ->firstOrFail();
        // Kirim data ke view
    return view('penilaian_reviewers.update_penilaian', compact('usulan', 'indikatorPenilaians', 'penilaianReviewer', 'reviewer'));
}



public function lihatReviewLaporanKemajuan($id)
{
    // Ambil data usulan berdasarkan ID
    $laporanKemajuan = LaporanKemajuan::findOrFail($id);
    // Ambil data user yang sedang login
    $user = auth()->user();
    // Cari reviewer berdasarkan user login
    $reviewer = Reviewer::where('user_id', $user->id)->first();

    // Ambil jenis skema dari usulan
    $jenis_skema = $laporanKemajuan->jenis;

    // Ambil KriteriaPenilaian yang sesuai dengan jenis dan proses 'usulan'
    $matchingKriteria = KriteriaPenilaian::where('jenis', $jenis_skema)
                                         ->where('proses', 'Laporan Kemajuan')
                                         ->pluck('id');

    // Ambil semua IndikatorPenilaian berdasarkan KriteriaPenilaian yang sesuai
    $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
                                              ->whereIn('kriteria_id', $matchingKriteria)
                                              ->get();
   // Ambil data penilaian reviewer dengan kondisi where pada relasi
    $penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
    ->where('laporankemajuan_id', $laporanKemajuan->id)
    ->with(['formPenilaians' => function ($query) {
        $query->with('indikator');
    }])
    ->firstOrFail();
    // Kirim data ke view
    return view('penilaian_reviewers.update_penilaian_kemajuan', compact('laporanKemajuan', 'indikatorPenilaians', 'penilaianReviewer', 'reviewer'));
}


public function lihatReviewLaporanAkhir($id)
{
    // Ambil data usulan berdasarkan ID
    $laporanAkhir = LaporanAkhir::findOrFail($id);
    // Ambil data user yang sedang login
    $user = auth()->user();
    // Cari reviewer berdasarkan user login
    $reviewer = Reviewer::where('user_id', $user->id)->first();

    // Ambil jenis skema dari usulan
    $jenis_skema = $laporanAkhir->jenis;

    // Ambil KriteriaPenilaian yang sesuai dengan jenis dan proses 'usulan'
    $matchingKriteria = KriteriaPenilaian::where('jenis', $jenis_skema)
                                         ->where('proses', 'Laporan Akhir')
                                         ->pluck('id');

    // Ambil semua IndikatorPenilaian berdasarkan KriteriaPenilaian yang sesuai
    $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')
                                              ->whereIn('kriteria_id', $matchingKriteria)
                                              ->get();
   // Ambil data penilaian reviewer dengan kondisi where pada relasi
    $penilaianReviewer = PenilaianReviewer::where('reviewer_id', $reviewer->id)
    ->where('laporanakhir_id', $laporanAkhir->id)
    ->with(['formPenilaians' => function ($query) {
        $query->with('indikator');
    }])
    ->firstOrFail();
    // Kirim data ke view
    return view('penilaian_reviewers.update_penilaian_akhir', compact('laporanAkhir', 'indikatorPenilaians', 'penilaianReviewer', 'reviewer'));
}


public function updateStatus($id, Request $request)
{
    // Validasi input status
    $request->validate([
        'status' => 'required|string|in:Diterima,Di Revisi Kembali',
    ]);
    // Cari PenilaianReviewer berdasarkan ID
    $penilaianReviewer = PenilaianReviewer::findOrFail($id);
    // Update status_penilaian sesuai dengan input dari form
    $penilaianReviewer->status_penilaian = $request->input('status');
    $penilaianReviewer->save();

    // update status laporan kemajuan =  jika direvisi kembali
    if ($request->input('status') === 'Di Revisi Kembali') {
        $penilaianReviewer->laporanKemajuan->status = 'revision';
        $penilaianReviewer->laporanKemajuan->save();
    }

    return redirect()->back()
                     ->with('success', 'Status penilaian berhasil diperbarui.');
}

}
