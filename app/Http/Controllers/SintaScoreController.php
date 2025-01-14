<?php

namespace App\Http\Controllers;

use App\Models\SintaScore;
use Illuminate\Http\Request;
use App\Imports\SintaScoresImport;
use App\Exports\SintaScoresExport;

use Maatwebsite\Excel\Facades\Excel;

class SintaScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil tahun dari query string, jika tidak ada, gunakan null
        $tahun = $request->query('tahun');
        // Ambil data SintaScore
        $sintaScores = SintaScore::when($tahun, function ($query, $tahun) {
            return $query->where('tahun', $tahun);
        })->get();
        // Kembalikan data ke view 
        return view('sinta_score.index', compact('sintaScores'));
    }

    
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xls,xlsx',
    ]);

    $import = new SintaScoresImport();
    Excel::import($import, $request->file('file'));

    // Ambil kesalahan jika ada
    $failures = $import->getFailures();

    if (count($failures) > 0) {
        // Menggunakan flash message untuk menyimpan kesalahan
        session()->flash('errors', $failures);
        return redirect()->back()->withInput();
    }

    return redirect()->route('sinta-score.index')->with('success', 'Data Sinta Score berhasil diimport.');
}

public function getSintaScore($nidn)
{
    $sintaScore = Sintascore::where('nidn', $nidn)->first();

    if ($sintaScore) {
        return response()->json(['score_sinta' => $sintaScore->sintascorev3]);
    }

    return response()->json(['score_sinta' => null]);
}



    public function export()
{
    $currentYear = date('Y'); // Ambil tahun saat ini
    return Excel::download(new SintaScoresExport(), 'sinta_scores_export_' . $currentYear . '.xlsx');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SintaScore $sintaScore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SintaScore $sintaScore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSintaScoreRequest $request, SintaScore $sintaScore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SintaScore $sintaScore)
    {
        //
    }
}
