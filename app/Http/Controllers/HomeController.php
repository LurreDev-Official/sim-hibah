<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usulan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanAkhir;
use App\Models\Reviewer;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use Carbon\Carbon;

// Ambil tahun saat ini
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard based on the user's role.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
    // Panggil fungsi untuk update semua dosen terkait proposal
    // $result = $this->updateJumlahProposalForAllDosen();
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek role user dan arahkan ke view yang sesuai
        if ($user->hasRole('Kepala LPPM')) {
    $countUsulan = Usulan::where('jenis_skema', 'penelitian')->count();
    $countPerbaikanUsulan = Usulan::where('jenis_skema', 'penelitian')->where('status', 'perbaikan')->count();
    $countLaporanKemajuan = LaporanKemajuan::count();
    $countLaporanAkhir = LaporanAkhir::count();

    // Hitung data untuk Pengabdian
    $countUsulanPengabdian = Usulan::where('jenis_skema', 'pengabdian')->count();
    $countPerbaikanUsulanPengabdian = Usulan::where('status', 'perbaikan')->count();
    $countLaporanKemajuanPengabdian = LaporanKemajuan::count();
    $countLaporanAkhirPengabdian = LaporanAkhir::count();
    $dosenData = Dosen::all();
  

    return view('dashboard.index', compact(
        'user' ,
        'dosenData',
        'countUsulan',
        'countLaporanKemajuan',
        'countLaporanAkhir',
        'countUsulanPengabdian',
        'countPerbaikanUsulanPengabdian',
        'countLaporanKemajuanPengabdian',
        'countLaporanAkhirPengabdian'
    ));
        } elseif ($user->hasRole('Dosen')) {
            // Cek apakah data dosen sudah ada untuk user_id tertentu
                $dosenData = Dosen::where('user_id', $user->id)->first();
                
                // Jika data dosen tidak ada, arahkan ke form edit profil dosen
                if (!$dosenData) {
                    return redirect()->route('profile.edit', ['id' => $user->id])->with('message', 'Harap lengkapi profil dosen Anda.');
                }
                $currentYear = Carbon::now()->year;
                // Jika data dosen sudah ada, tampilkan halaman dashboard
                $countPenelitian = Usulan::where('ketua_dosen_id', $dosenData->id)
                    ->where('tahun_pelaksanaan', '=', $currentYear) // Misalnya tahun 2023

                    ->where('jenis_skema', 'penelitian')
                    ->count();
                $countPengabdian = Usulan::where('ketua_dosen_id', $dosenData->id)
                    ->where('jenis_skema', 'pengabdian')
                    ->where('tahun_pelaksanaan', '=', $currentYear) // Misalnya tahun 2023

                    ->count();

                   


                    $usulanPenelitian = Usulan::where('jenis_skema', 'penelitian')
                    ->whereYear('tahun_pelaksanaan', '=', $currentYear) // Misalnya tahun 2023
                    ->get();
                    $countAnggota2ProposalPenelitian = AnggotaDosen::whereIn('usulan_id', $usulanPenelitian->pluck('id'))
                    ->where('status_anggota', 'anggota') // Hanya anggota dengan status 'anggota'
                    ->where('dosen_id', Auth::user()->id) // Hanya untuk dosen yang sedang login
                    ->get();      
                    
                    $usulanPengabdian = Usulan::where('jenis_skema', 'pengabdian')
                    ->whereYear('tahun_pelaksanaan', '=', $currentYear) // Misalnya tahun 2023
                    ->get();
                    $countAnggota2ProposalPengabdian = AnggotaDosen::whereIn('usulan_id', $usulanPengabdian->pluck('id'))
                    ->where('status_anggota', 'anggota') // Hanya anggota dengan status 'anggota'
                    ->where('dosen_id', Auth::user()->id) // Hanya untuk dosen yang sedang login
                    ->get();

                  
                return view('dashboard.index', compact(
                    'user' ,
                    'dosenData',
                    'countPenelitian',
                    'countPengabdian',
                    'countAnggota2ProposalPenelitian',
                    'countAnggota2ProposalPengabdian'
                  
                ));
        } elseif ($user->hasRole('Reviewer')) {
            
            $getreviewer = Reviewer::where('user_id', $user->id)->first();
            if (!$getreviewer) {
                return redirect()->route('profile.edit', ['id' => $user->id])->with('message', 'Harap lengkapi profil dosen Anda.');
            }
            $notifreview = \App\Models\PenilaianReviewer::where('reviewer_id', $getreviewer->id)
                ->where('status_penilaian', 'Belum Dinilai') // Filter status penilaian
                ->where(function($query) {
                    // Filter berdasarkan apakah salah satu id (usulan, laporan kemajuan, laporan akhir) tidak null
                    $query->whereNotNull('usulan_id')
                          ->orWhereNotNull('laporankemajuan_id')
                          ->orWhereNotNull('laporanakhir_id');
                })
                ->with('usulan','laporankemajuan','laporanakhir') // Pastikan relasi 'usulan' didefinisikan di PenilaianReviewer
                ->get();
        
            return view('dashboard.reviewer', [

                'notifreview' => $notifreview,
                'user' => $user,
                'countUsulan' => Usulan::count(),
                'countLaporanKemajuan' => LaporanKemajuan::count(),
                'countLaporanAkhir' => LaporanAkhir::count(),
            ]);
        }

        // Jika user tidak memiliki role yang spesifik, kembalikan ke dashboard default
        return view('dashboard.index', [
            'user' => $user,
        ]);
    }


    public function verifikasiQR($scannedData)
{
    // Dekripsi data dari QR Code
    try {
        $decryptedData = Crypt::decryptString($scannedData);
        $dataArray = json_decode($decryptedData, true);
        
        // Akses ID dan timestamp dari data yang dipindai
        $idUsulan = $dataArray['id'];
        $timestamp = $dataArray['timestamp'];

        // Verifikasi ID dan timestamp sesuai dengan data yang valid
        $laporanKemajuan = LaporanKemajuan::find($idUsulan);
        if ($laporanKemajuan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data valid',
                'data' => $dataArray
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    } catch (\Exception $e) {
        // Menangani kesalahan dekripsi atau JSON
        return response()->json([
            'status' => 'error',
            'message' => 'QR Code tidak valid atau data rusak'
        ]);
    }
}
 


public function updateJumlahProposalForAllDosen()
{
    // Ambil semua dosen dari tabel dosens
    $dosens = Dosen::all();

    // Looping melalui semua dosen
    foreach ($dosens as $dosen) {
        // Hitung jumlah usulan sebagai ketua untuk tahun ini
        $usulanKetua = Usulan::where('ketua_dosen_id', $dosen->id)
            ->whereYear('tahun_pelaksanaan', Carbon::now()->year)
            ->count();

        // Hitung jumlah usulan sebagai anggota untuk tahun ini
        $usulanAnggota = AnggotaDosen::where('dosen_id', $dosen->id)
            ->whereIn('status_anggota', ['anggota'])
            ->whereHas('proposal', function($query) {
                $query->whereYear('tahun_pelaksanaan', Carbon::now()->year);
            })
            ->count();

        // Hitung total usulan (ketua + anggota)
        $totalUsulan = $usulanKetua + $usulanAnggota;
        // dd($totalUsulan);

        // Update jumlah_proposal dosen
      
        // Cari dosen berdasarkan dosen_id
            $dosen = Dosen::find($dosen->id);
            $dosen->jumlah_proposal = $totalUsulan;
            $dosen->save();

    }

    return response()->json(['success' => 'Jumlah proposal untuk semua dosen telah diperbarui.']);
}


}
