<?php

namespace App\Http\Controllers;

use App\Models\KriteriaPenilaian;
use Illuminate\Http\Request;

class KriteriaPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data kriteria penilaian dari database
        $kriteriaPenilaians = KriteriaPenilaian::all();
        return view('kriteria_penilaian.index', compact('kriteriaPenilaians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'proses' => 'required|string|max:255',
        ]);

        // Membuat kriteria penilaian baru
        KriteriaPenilaian::create($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('kriteria-penilaian.index')
            ->with('success', 'Kriteria Penilaian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KriteriaPenilaian $kriteriaPenilaian)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KriteriaPenilaian $kriteriaPenilaian)
    {
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KriteriaPenilaian $kriteriaPenilaian)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'proses' => 'required|string|max:255',
     
        ]);

        // Update kriteria penilaian
        $kriteriaPenilaian->update($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('kriteria-penilaian.index')
            ->with('success', 'Kriteria Penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    // Temukan data kriteria berdasarkan ID
    $kriteria = KriteriaPenilaian::find($id);

    // Jika data tidak ditemukan, kembalikan pesan error
    if (!$kriteria) {
        return redirect()->route('kriteria-penilaian.index')
            ->with('error', 'Kriteria Penilaian tidak ditemukan.');
    }

    // Hapus data kriteria
    $kriteria->delete();

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('kriteria-penilaian.index')
        ->with('success', 'Kriteria Penilaian berhasil dihapus.');
}

}
