<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Dosen;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreDosenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dosen $dosen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        // Validasi data yang masuk
        $request->validate([
            'nidn' => 'required|string|max:255',
            'kuota_proposal' => 'required|integer',
            'jumlah_proposal' => 'required|integer',
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'score_sinta' => 'nullable|numeric',
        ]);

        // Update data dosen
        $dosen->update([
            'nidn' => $request->input('nidn'),
            'kuota_proposal' => $request->input('kuota_proposal'),
            'jumlah_proposal' => $request->input('jumlah_proposal'),
            'fakultas' => $request->input('fakultas'),
            'prodi' => $request->input('prodi'),
            'score_sinta' => $request->input('score_sinta'),
        ]);

        // Redirect atau response setelah update berhasil
        return redirect()->route('dosen.show', $dosen)->with('success', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        //
    }


    

}
