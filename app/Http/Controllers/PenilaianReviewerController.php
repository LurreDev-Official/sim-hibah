<?php

namespace App\Http\Controllers;

use App\Models\PenilaianReviewer;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use App\Models\AnggotaMahasiswa;
use App\Models\Reviewer;
use App\Models\FormPenilaian;
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
        // Validate incoming request data
        $validated = $request->validate([
            'usulan_id' => 'required|integer',
            'status_penilaian' => 'required|string',
            'form_penilaian_id' => 'required|integer',
            'reviewer_id' => 'required|integer',
        ]);

        // Update the existing PenilaianReviewer record
        $penilaianReviewer->update($validated);

        // Redirect back to the index page with a success message
        return redirect()->route('penilaian_reviewers.index')->with('success', 'Penilaian Reviewer updated successfully.');
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



    /**
 * Menampilkan daftar usulan untuk direview.
 *
 * @return \Illuminate\View\View
 */
public function indexReviewUsulan()
{
    $reviewer = Reviewer::where('user_id', auth()->id())->first();

    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }

    $usulans = PenilaianReviewer::where('reviewer_id', $reviewer->id)
        ->where('status_penilaian', 'Pending')
        ->with('usulan')
        ->paginate(10);

    return view('penilaian_reviewers.review_usulan', compact('usulans'));
}

/**
 * Menampilkan daftar laporan kemajuan untuk direview.
 *
 * @return \Illuminate\View\View
 */
public function indexReviewLaporanKemajuan()
{
    $reviewer = Reviewer::where('user_id', auth()->id())->first();

    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }

    $laporanKemajuan = PenilaianReviewer::where('reviewer_id', $reviewer->id)
        ->where('status_penilaian', 'Laporan Kemajuan')
        ->with('usulan')
        ->paginate(10);

    return view('penilaian_reviewers.review_laporan_kemajuan', compact('laporanKemajuan'));
}

/**
 * Menampilkan daftar laporan akhir untuk direview.
 *
 * @return \Illuminate\View\View
 */
public function indexReviewLaporanAkhir()
{
    $reviewer = Reviewer::where('user_id', auth()->id())->first();

    if (!$reviewer) {
        return redirect()->back()->with('error', 'Reviewer tidak ditemukan.');
    }

    $laporanAkhir = PenilaianReviewer::where('reviewer_id', $reviewer->id)
        ->where('status_penilaian', 'Laporan Akhir')
        ->with('usulan')
        ->paginate(10);

    return view('penilaian_reviewers.review_laporan_akhir', compact('laporanAkhir'));
}



}
