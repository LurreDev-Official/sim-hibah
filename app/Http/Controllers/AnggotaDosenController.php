<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggotaDosen;
use App\Models\Dosen;

class AnggotaDosenController extends Controller
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
            'usulan_id' => 'required|exists:usulans,id', // Usahakan sesuai dengan field yang diperlukan
            'dosen_id' => 'required|exists:dosens,id',  // Pastikan tabel dan field dosen sesuai
            'jenis' => 'required|in:penelitian,pengabdian', // Jenis anggota harus ketua atau anggota
                       
        ]);

        $usulanAnggota = AnggotaDosen::where('dosen_id', $request->dosen_id)
        ->whereIn('usulan_id', $usulan_id) // Filter berdasarkan usulan_id yang sudah diambil
        ->where('status', 'approved') // Status usulan harus approved
        ->where('status_anggota', 'anggota') // Hanya hitung sebagai anggota
        ->where('jenis_skema', $jenis) // Filter berdasarkan jenis skema
        ->count();

        // Validasi: Batasi maksimal 1 usulan sebagai anggota per skema
        if ($usulanAnggota >= 1) {
            return back()->with('error', "Anda sudah menjadi anggota di 1 usulan proposal untuk skema $jenis pada tahun ini.");
        }

        // Cek apakah dosen sudah terdaftar sebagai anggota
                                $count = AnggotaDosen::
                                where('dosen_id', $request->dosen_id)
                                ->where('usulan_id', $usulan_id)
                                ->where('jenis_skema', $request->jenis)
                                ->count();

                                if ($count >= 1) {
                                    return redirect()->back()->with('error', 'Dosen hanya memiliki 1 kuota sebagai anggota.');
                                }

        // Simpan data ke dalam database
        AnggotaDosen::create([
            'usulan_id' => $request->usulan_id,
            'jenis_skema' => $request->jenis,
            'dosen_id' => $request->dosen_id,
            'status_anggota' => 'anggota',
            'status' => 'belum disetujui',
        ]);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data dosen berhasil ditambahkan');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(AnggotaDosen $anggotaDosen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnggotaDosen $anggotaDosen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnggotaDosen $anggota_dosen)
    {
         
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $dosen = AnggotaDosen::findOrFail($id);
    $dosen->delete();
    return redirect()->back()->with('success', 'Data dosen berhasil dihapus');
}


    public function getDosen(Request $request)
    {
        // Ambil data dosen beserta nama dari tabel users
        $dosen = Dosen::with('user:id,name')->get(); // Mengambil dosen dan nama dari relasi user
    
        if ($dosen->isEmpty()) {
            \Log::warning('Data dosen tidak ditemukan'); // Log jika data dosen kosong
        }
    
        // Ubah data menjadi format yang dapat digunakan oleh Select2
        $data = $dosen->map(function ($d) {
            return [
                'id' => $d->id,  // Mengirimkan dosen ID
                'nama_dosen' => $d->user->name  // Mengambil nama dari tabel users melalui relasi user
            ];
        });
    
        // Log data yang akan dikirim
        \Log::info('Data dosen yang dikirim:', $data->toArray());
    
        // Kirim data dalam format JSON untuk Select2
        return response()->json($data);
    }


    public function approve($usulan_id, $anggota_dosen_id)
{
    // Temukan anggota dosen berdasarkan usulan_id dan id anggota_dosen
    $anggotaDosen = AnggotaDosen::where('usulan_id', $usulan_id)
                                ->where('id', $anggota_dosen_id)
                                ->firstOrFail();

    // Update status menjadi 'setuju'
    $anggotaDosen->status = 'terima';
    $anggotaDosen->save();

    return redirect()->back()->with('success', 'Anggota dosen berhasil disetujui.');
}

public function reject($usulan_id, $anggota_dosen_id)
{
    // Temukan anggota dosen berdasarkan usulan_id dan id anggota_dosen
    $anggotaDosen = AnggotaDosen::where('usulan_id', $usulan_id)
                                ->where('id', $anggota_dosen_id)
                                ->firstOrFail();

    // Update status menjadi 'tolak'
    $anggotaDosen->status = 'tolak';
    $anggotaDosen->save();

    return redirect()->back()->with('success', 'Anggota dosen berhasil ditolak.');
}

    
}


