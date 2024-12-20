<?php

namespace App\Http\Controllers;

 
use App\Models\AnggotaMahasiswa;
use Illuminate\Http\Request;

class AnggotaMahasiswaController extends Controller
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
    public function store(Request $request)
    {
         // Validasi input
    $request->validate([
        'usulan_id' => 'required|exists:usulans,id',
        'nim' => 'required|string|max:20|unique:anggota_mahasiswas,nim',
        'nama_lengkap' => 'required|string|max:255',
        'fakultas' => 'required|string|max:255',
        'prodi' => 'required|string|max:255',
    ]);

    // Simpan data ke dalam database
    AnggotaMahasiswa::create([
        'usulan_id' => $request->usulan_id,
        'nim' => $request->nim,
        'nama_lengkap' => $request->nama_lengkap,
        'fakultas' => $request->fakultas,
        'prodi' => $request->prodi,
    ]);

    // Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Data mahasiswa berhasil ditambahkan');

    
         
    }
    

    /**
     * Display the specified resource.
     */
    public function show(AnggotaMahasiswa $anggotaMahasiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnggotaMahasiswa $anggotaMahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnggotaMahasiswaRequest $request, AnggotaMahasiswa $anggotaMahasiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cari data anggota mahasiswa berdasarkan id
        $anggotaMahasiswa = AnggotaMahasiswa::findOrFail($id);
    
        // Hapus data
        $anggotaMahasiswa->delete();
    
        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data mahasiswa berhasil dihapus');
    }
    
}
