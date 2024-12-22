<?php

namespace App\Http\Controllers;

use App\Models\LaporanKemajuan;
use Illuminate\Http\Request;
 

class LaporanKemajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil filter 'jenis' dari query string
        $jenis = $request->input('jenis');

        // Query data berdasarkan jenis jika ada filter
        $laporanKemajuan = LaporanKemajuan::when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        })->get();

        // Kembalikan ke view dengan data yang difilter
        return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan form untuk menambah data
        return view('laporan_kemajuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'ketua_dosen_id' => 'required|exists:dosens,id',
        'usulan_id' => 'required|exists:usulans,id',
        'dokumen_laporan_kemajuan' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'status' => 'required|string',
        'jenis' => 'required|in:Penelitian,Pengabdian',
    ]);

    // Simpan file ke folder public/uploads
    if ($request->hasFile('dokumen_laporan_kemajuan')) {
        $filePath = $request->file('dokumen_laporan_kemajuan')->store('uploads', 'public');
        $validated['dokumen_laporan_kemajuan'] = $filePath;
    }

    // Simpan data ke database
    LaporanKemajuan::create($validated);

    return redirect()->route('laporan-kemajuan.index')->with('success', 'Laporan kemajuan berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        // Query data berdasarkan jenis jika ada filter
        $laporanKemajuan = LaporanKemajuan::when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        })->get();

        // Kembalikan ke view dengan data yang difilter
        return view('laporan_kemajuan.index', compact('laporanKemajuan', 'jenis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Temukan laporan kemajuan berdasarkan id
        $laporanKemajuan = LaporanKemajuan::findOrFail($id);

        // Tampilkan form edit dengan data yang dipilih
        return view('laporan_kemajuan.edit', compact('laporanKemajuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'ketua_dosen_id' => 'required|exists:dosens,id',
        'usulan_id' => 'required|exists:usulans,id',
        'dokumen_laporan_kemajuan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        'status' => 'required|string',
        'jenis' => 'required|in:Penelitian,Pengabdian',
    ]);

    $laporanKemajuan = LaporanKemajuan::findOrFail($id);

    // Simpan file baru jika ada
    if ($request->hasFile('dokumen_laporan_kemajuan')) {
        // Hapus file lama jika ada
        if ($laporanKemajuan->dokumen_laporan_kemajuan && \Storage::disk('public')->exists($laporanKemajuan->dokumen_laporan_kemajuan)) {
            \Storage::disk('public')->delete($laporanKemajuan->dokumen_laporan_kemajuan);
        }

        // Simpan file baru
        $filePath = $request->file('dokumen_laporan_kemajuan')->store('uploads', 'public');
        $validated['dokumen_laporan_kemajuan'] = $filePath;
    }

    // Perbarui data
    $laporanKemajuan->update($validated);

    return redirect()->route('laporan-kemajuan.index')->with('success', 'Laporan kemajuan berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $laporanKemajuan = LaporanKemajuan::findOrFail($id);

    // Hapus file dari storage jika ada
    if ($laporanKemajuan->dokumen_laporan_kemajuan && \Storage::disk('public')->exists($laporanKemajuan->dokumen_laporan_kemajuan)) {
        \Storage::disk('public')->delete($laporanKemajuan->dokumen_laporan_kemajuan);
    }

    // Hapus data dari database
    $laporanKemajuan->delete();

    return redirect()->route('laporan-kemajuan.index')->with('success', 'Laporan kemajuan berhasil dihapus.');
}

}