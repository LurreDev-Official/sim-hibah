<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    // Menampilkan daftar periode
    public function index()
    {
        $periodes = Periode::all();
        return view('periodes.index', compact('periodes'));
    }

    // Menampilkan form untuk membuat periode baru
    public function create()
    {
        return view('periodes.create');
    }

    // Menyimpan periode yang baru dibuat
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2025|max:' . date('Y'), // Ensure the year is between 2025 and the current year
            'tanggal_awal' => 'required|date|before_or_equal:tanggal_akhir', // Ensure tanggal_awal is before or equal to tanggal_akhir
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal', // Ensure tanggal_akhir is after or equal to tanggal_awal
            'nominal' => 'required|numeric|min:0', // Ensure nominal is a valid number and non-negative
            'is_active' => 'required|boolean', // Ensure is_active is either 1 or 0
        ]);
    
        // If validation passes, create a new Periode
        Periode::create($validated);
    
        // Redirect back to the index page with a success message
        return redirect()->route('periodes.index')->with('success', 'Periode berhasil ditambahkan.');
    }
    

    // Menampilkan form untuk mengedit periode yang sudah ada
    public function edit(Periode $periode)
    {
        return view('periodes.edit', compact('periode'));
    }

    // Mengupdate periode yang ada
    public function update(Request $request, Periode $periode)
{
    // Validate the incoming request data
    $validated = $request->validate([
        'tahun' => 'required|integer|min:2025|max:' . date('Y'), // Ensure the year is between 2025 and the current year
        'tanggal_awal' => 'required|date|before_or_equal:tanggal_akhir', // Ensure tanggal_awal is before or equal to tanggal_akhir
        'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal', // Ensure tanggal_akhir is after or equal to tanggal_awal
        'nominal' => 'required|numeric|min:0', // Ensure nominal is a valid number and non-negative
        'is_active' => 'required|boolean', // Ensure is_active is either 1 or 0
    ]);

    // Update the Periode instance with the validated data
    $periode->update($validated);

    // Redirect to the index page with a success message
    return redirect()->route('periodes.index')->with('success', 'Periode berhasil diperbarui.');
}


    // Menghapus periode
    public function destroy(Periode $periode)
    {
        $periode->delete();

        return redirect()->route('periodes.index')->with('success', 'Periode berhasil dihapus.');
    }
}
