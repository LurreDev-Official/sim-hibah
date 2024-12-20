<?php

namespace App\Http\Controllers;

use App\Models\IndikatorPenilaian;
use App\Models\KriteriaPenilaian;
use Illuminate\Http\Request;

class IndikatorPenilaianController extends Controller
{
    public function index()
    {
        $kriteriaPenilaians = KriteriaPenilaian::all();
        $indikatorPenilaians = IndikatorPenilaian::with('kriteriaPenilaian')->paginate(10);
        return view('indikatorpenilaian.index', compact('indikatorPenilaians','kriteriaPenilaians'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required',
            'nama_indikator' => 'required',
            'jumlah_bobot' => 'required|integer',
            'score' => 'nullable|numeric',
            'sub_total' => 'nullable|numeric',
        ]);

        IndikatorPenilaian::create($request->all());

        return redirect()->route('indikator-penilaian.index')
            ->with('success', 'Indikator Penilaian berhasil ditambahkan.');
    }

    public function edit(IndikatorPenilaian $indikatorPenilaian)
    {
       
    }

    public function update(Request $request, IndikatorPenilaian $indikatorPenilaian)
    {
        $request->validate([
            'kriteria_id' => 'required',
            'nama_indikator' => 'required',
            'jumlah_bobot' => 'required|integer',
            'score' => 'nullable|numeric',
            'sub_total' => 'nullable|numeric',
        ]);

        $indikatorPenilaian->update($request->all());

        return redirect()->route('indikator-penilaian.index')
            ->with('success', 'Indikator Penilaian berhasil diperbarui.');
    }

    public function destroy(IndikatorPenilaian $indikatorPenilaian)
    {
        $indikatorPenilaian->delete();

        return redirect()->route('indikator-penilaian.index')
            ->with('success', 'Indikator Penilaian berhasil dihapus.');
    }
}
