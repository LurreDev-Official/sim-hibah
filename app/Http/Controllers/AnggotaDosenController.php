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
        ->where('status', 'terima') // Status usulan harus approved
        ->where('status_anggota', 'anggota') // Hanya hitung sebagai anggota
        ->where('jenis_skema', $request->jenis) // Filter berdasarkan jenis skema
        ->count();

        // dd($usulanAnggota);

        // Validasi: Batasi maksimal 1 usulan sebagai anggota per skema
        if ($usulanAnggota ==1) {
            return back()->with('error', "Anda sudah menjadi anggota di 1 usulan proposal untuk skema $request->jenis pada tahun ini.");
        }else{
            // Simpan data ke dalam database
        AnggotaDosen::create([
            'usulan_id' => $request->usulan_id,
            'jenis_skema' => $request->jenis,
            'dosen_id' => $request->dosen_id,
            'status_anggota' => 'anggota',
            'status' => 'belum disetujui',
        ]);

        return redirect()->back()->with('success', 'Data dosen berhasil ditambahkan');

        }

        // // Cek apakah dosen sudah terdaftar sebagai anggota
        //                         $count = AnggotaDosen::
        //                        where('usulan_id',  $request->usulan_id)->
        //                         where('dosen_id', $request->dosen_id)
        //                         ->where('usulan_id',  $request->usulan_id)
        //                         ->where('jenis_skema', $request->jenis)
        //                         ->count();

        //                         if ($count >= 1) {
        //                             return redirect()->back()->with('error', 'Dosen hanya memiliki 1 kuota sebagai anggota.');
        //                         }
        // // Redirect kembali dengan pesan sukses
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


    public function approves($usulan_id, $anggota_dosen_id)
{
    // Temukan anggota dosen berdasarkan usulan_id dan id anggota_dosen
    $anggotaDosen = AnggotaDosen::where('usulan_id', $usulan_id)
                                ->where('id', $anggota_dosen_id)
                                ->firstOrFail();

    // Update status menjadi 'setuju'

    //hitung AggotaDosen pernah jadi ketua jika pernah maka kesempatan jadi anggota 1 kali
    $hitungketua = AnggotaDosen::where('dosen_id', $anggotaDosen->dosen_id)
        ->where('status_anggota', 'ketua')
        ->where('status', 'terima')
        ->count();

        $hitungjumlahstatusanggota = AnggotaDosen::where('dosen_id', $anggotaDosen->dosen_id)
        ->where('status_anggota', 'ketua')
        ->where('status', 'terima')
        ->count();
    // Jika sudah pernah jadi ketua maka kesempatan jadi anggota 1 kali
    if ($ketua > 0) {
        $anggotaDosen->status = 'terima';
        $anggotaDosen->save();
        return redirect()->back()->with('success', 'Anggota dosen berhasil disetujui.');
    } else {
        $anggotaDosen->status_anggota = 'ketua';
    }
    // Update status menjadi 'terima'
   
}

public function approve($usulan_id, $anggota_dosen_id)
{
    //ambil jenis skema dari usulan_id
    $usulan = Usulan::find($usulan_id);
    $jenis_skema = $usulan->jenis_skema;
    // Temukan anggota dosen berdasarkan usulan_id dan id anggota_dosen
    $anggotaDosen = AnggotaDosen::where('usulan_id', $usulan_id)
                                ->where('id', $anggota_dosen_id)
                                ->where('jenis_skema', $jenis_skema)
                                ->firstOrFail();
    
    // Ambil data dosen
    $dosen = Dosen::find($anggotaDosen->dosen_id);
    
    // Cek apakah kuota masih tersedia
    if ($dosen->kuota_proposal <= 0) {
        return redirect()->back()->with('error', 'Kuota proposal dosen sudah habis.');
    }

    // Hitung berapa kali dosen pernah jadi ketua
    $hitungKetua = AnggotaDosen::where('dosen_id', $anggotaDosen->dosen_id)
        ->where('status_anggota', 'ketua')
        ->where('jenis_skema', $jenis_skema)
        ->where('status', 'terima')
        ->count();
    
    // Hitung berapa kali dosen sudah jadi anggota
    $hitungAnggota = AnggotaDosen::where('dosen_id', $anggotaDosen->dosen_id)
        ->where('status_anggota', 'anggota')
        ->where('jenis_skema', $jenis_skema)
        ->where('status', 'terima')
        ->count();
    
    // Tentukan status berdasarkan ketentuan
    if ($anggotaDosen->status_anggota == 'anggota') {
        // Jika posisinya adalah anggota
        if ($hitungKetua > 0 && $hitungAnggota < 1) {
            // Jika pernah jadi ketua dan belum pernah jadi anggota (batas 1 kali)
            $anggotaDosen->status = 'terima';
        } else if ($hitungKetua == 0 && $hitungAnggota < 2) {
            // Jika belum pernah jadi ketua dan belum jadi anggota 2 kali (batas 2 kali)
            $anggotaDosen->status = 'terima';
        } else {
            // Jika sudah melebihi batas
            return redirect()->back()->with('error', 'Dosen sudah mencapai batas maksimum keterlibatan dalam skema ini. silahkan ditolak');
        }
    }
    // Simpan perubahan
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


