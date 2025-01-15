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
use Illuminate\Support\Facades\DB;
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

        // /grafik

         // Ambil jumlah usulan berdasarkan fakultas, dengan filter jenis_skema 'penelitian'
    $usulanPerFakultas = Usulan::with('ketuaDosen') // Mengambil ketua dosen untuk mendapatkan fakultas
    ->where('jenis_skema', 'penelitian') // Filter jenis_skema 'penelitian'
    ->select(DB::raw('count(*) as usulan_count, ketua_dosen_id'))
    ->groupBy('ketua_dosen_id') // Kelompokkan berdasarkan ketua dosen
    ->get();

// Ambil data fakultas dan hitung jumlah usulan per fakultas
$fakultasData = [];
foreach ($usulanPerFakultas as $data) {
    // Ambil nama fakultas dari ketua dosen
    $fakultas = $data->ketuaDosen->fakultas;
    
    // Menambahkan data fakultas dan jumlah usulan ke array
    if (isset($fakultasData[$fakultas])) {
        $fakultasData[$fakultas] += $data->usulan_count;
    } else {
        $fakultasData[$fakultas] = $data->usulan_count;
    }
}

// Mengubah data menjadi format yang siap ditampilkan di grafik
$fakultasDataFormatted = [];
foreach ($fakultasData as $fakultas => $count) {
    $fakultasDataFormatted[] = [
        'fakultas' => $fakultas, 
        'usulan_count' => $count
    ];
}

                
    // Panggil fungsi untuk update semua dosen terkait proposal
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
        'fakultasDataFormatted',
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
    $result = $this->updateJumlahProposalForAllDosen();

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
        'fakultasDataFormatted',
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

    // Mendapatkan data Dosen yang sedang login
$dosenData = Auth::user()->dosen;

// Tahun pelaksanaan saat ini
$currentYear = now()->year;

// Menghitung jumlah proposal Penelitian sebagai Ketua
$countPenelitian = Usulan::where('ketua_dosen_id', $dosenData->id)
    ->where('tahun_pelaksanaan', '=', $currentYear)
    ->where('jenis_skema', 'penelitian')
    ->count();

// Menghitung jumlah proposal Pengabdian sebagai Ketua
$countPengabdian = Usulan::where('ketua_dosen_id', $dosenData->id)
    ->where('tahun_pelaksanaan', '=', $currentYear)
    ->where('jenis_skema', 'pengabdian')
    ->count();

// Mendapatkan usulan penelitian tahun ini
$usulanPenelitian = Usulan::where('jenis_skema', 'penelitian')
    ->whereYear('tahun_pelaksanaan', '=', $currentYear)
    ->get();

// Menghitung jumlah proposal penelitian sebagai Anggota
$countAnggota2ProposalPenelitian = AnggotaDosen::whereIn('usulan_id', $usulanPenelitian->pluck('id'))
    ->where('status_anggota', 'anggota')
    ->where('dosen_id', $dosenData->id)
    ->count(); // Gunakan count() untuk mendapatkan jumlah elemen

// Mendapatkan usulan pengabdian tahun ini
$usulanPengabdian = Usulan::where('jenis_skema', 'pengabdian')
    ->whereYear('tahun_pelaksanaan', '=', $currentYear)
    ->get();

// Menghitung jumlah proposal pengabdian sebagai Anggota
$countAnggota2ProposalPengabdian = AnggotaDosen::whereIn('usulan_id', $usulanPengabdian->pluck('id'))
    ->where('status_anggota', 'anggota')
    ->where('dosen_id', $dosenData->id)
    ->count(); // Gunakan count() untuk mendapatkan jumlah elemen

// Total semua count
$totalProposal = $countPenelitian + $countPengabdian + $countAnggota2ProposalPenelitian + $countAnggota2ProposalPengabdian;

// Update jumlah_proposal di Model Dosen
$dosenData->update([
    'jumlah_proposal' => $totalProposal,
    'kuota_proposal' => 4-$totalProposal
]);


}


}
