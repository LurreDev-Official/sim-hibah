<?php

namespace App\Http\Controllers;

use App\Models\LaporanAkhir;
use Illuminate\Http\Request;

class LaporanAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan jenis (opsional)
        $jenis = $request->input('jenis');
        $laporanAkhirs = LaporanAkhir::when($jenis, function ($query, $jenis) {
            $query->where('jenis', $jenis);
        })->get();

        // Tampilkan ke view
        return view('laporan_akhir.index', compact('laporanAkhirs', 'jenis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan form untuk menambahkan laporan akhir
        return view('laporan_akhir.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'usulan_id' => 'required|exists:usulans,id',
            'dokumen_laporan_akhir' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'jenis' => 'required|in:Penelitian,Pengabdian',
            'status' => 'required|string',
        ]);

        // Simpan file
        if ($request->hasFile('dokumen_laporan_akhir')) {
            $validated['dokumen_laporan_akhir'] = $request->file('dokumen_laporan_akhir')->store('uploads/laporan_akhir', 'public');
        }

        // Simpan data ke database
        LaporanAkhir::create($validated);

        return redirect()->route('laporan-akhir.index')->with('success', 'Laporan akhir berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanAkhir $laporanAkhir)
    {
        // Tampilkan detail laporan akhir
        return view('laporan_akhir.show', compact('laporanAkhir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanAkhir $laporanAkhir)
    {
        // Tampilkan form untuk mengedit laporan akhir
        return view('laporan_akhir.edit', compact('laporanAkhir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanAkhir $laporanAkhir)
    {
        // Validasi data input
        $validated = $request->validate([
            'ketua_dosen_id' => 'required|exists:dosens,id',
            'usulan_id' => 'required|exists:usulans,id',
            'dokumen_laporan_akhir' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'jenis' => 'required|in:Penelitian,Pengabdian',
            'status' => 'required|string',
        ]);

        // Perbarui file jika ada
        if ($request->hasFile('dokumen_laporan_akhir')) {
            // Hapus file lama jika ada
            if ($laporanAkhir->dokumen_laporan_akhir && \Storage::disk('public')->exists($laporanAkhir->dokumen_laporan_akhir)) {
                \Storage::disk('public')->delete($laporanAkhir->dokumen_laporan_akhir);
            }
            // Simpan file baru
            $validated['dokumen_laporan_akhir'] = $request->file('dokumen_laporan_akhir')->store('uploads/laporan_akhir', 'public');
        }

        // Perbarui data di database
        $laporanAkhir->update($validated);

        return redirect()->route('laporan-akhir.index')->with('success', 'Laporan akhir berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanAkhir $laporanAkhir)
    {
        // Hapus file dari storage jika ada
        if ($laporanAkhir->dokumen_laporan_akhir && \Storage::disk('public')->exists($laporanAkhir->dokumen_laporan_akhir)) {
            \Storage::disk('public')->delete($laporanAkhir->dokumen_laporan_akhir);
        }

        // Hapus data dari database
        $laporanAkhir->delete();

        return redirect()->route('laporan-akhir.index')->with('success', 'Laporan akhir berhasil dihapus.');
    }
}
