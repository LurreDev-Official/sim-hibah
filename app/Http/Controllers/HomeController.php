<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usulan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanAkhir;
use App\Models\Reviewer;
use App\Models\Dosen;

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

    return view('dashboard.index', compact(
        'user' ,
        'countUsulan',
        'countPerbaikanUsulan',
        'countLaporanKemajuan',
        'countLaporanAkhir',
        'countUsulanPengabdian',
        'countPerbaikanUsulanPengabdian',
        'countLaporanKemajuanPengabdian',
        'countLaporanAkhirPengabdian'
    ));
        } elseif ($user->hasRole('Dosen')) {
            // Cek apakah data dosen sudah ada untuk user_id tertentu
                $dosen = Dosen::where('user_id', $user->id)->first();
                
                // Jika data dosen tidak ada, arahkan ke form edit profil dosen
    if (!$dosen) {
        return redirect()->route('profile.edit', ['id' => $user->id])->with('message', 'Harap lengkapi profil dosen Anda.');
    }

                // Jika data dosen sudah ada, tampilkan halaman dashboard
                $countUsulan = Usulan::where('jenis_skema', 'penelitian')->count();
                $countPerbaikanUsulan = Usulan::where('jenis_skema', 'penelitian')->where('status', 'perbaikan')->count();
                $countLaporanKemajuan = LaporanKemajuan::count();
                $countLaporanAkhir = LaporanAkhir::count();
            
                // Hitung data untuk Pengabdian
                $countUsulanPengabdian = Usulan::where('jenis_skema', 'pengabdian')->count();
                $countPerbaikanUsulanPengabdian = Usulan::where('status', 'perbaikan')->count();
                $countLaporanKemajuanPengabdian = LaporanKemajuan::count();
                $countLaporanAkhirPengabdian = LaporanAkhir::count();
            
                return view('dashboard.index', compact(
                    'user' ,
                    'countUsulan',
                    'countPerbaikanUsulan',
                    'countLaporanKemajuan',
                    'countLaporanAkhir',
                    'countUsulanPengabdian',
                    'countPerbaikanUsulanPengabdian',
                    'countLaporanKemajuanPengabdian',
                    'countLaporanAkhirPengabdian'
                ));
        } elseif ($user->hasRole('Reviewer')) {
            
            $data = Reviewer::where('user_id', $user->id)->first();
            if (!$data) {
                return redirect()->route('profile.edit', ['id' => $user->id])->with('message', 'Harap lengkapi profil dosen Anda.');
            }
            $notifreview = \App\Models\PenilaianReviewer::where('reviewer_id', $data->id)
                ->where('status_penilaian', 'Belum Dinilai') // Filter status penilaian
                ->where(function($query) {
                    // Filter berdasarkan apakah salah satu id (usulan, laporan kemajuan, laporan akhir) tidak null
                    $query->whereNotNull('usulan_id')
                          ->orWhereNotNull('laporankemajuan_id')
                          ->orWhereNotNull('laporanakhir_id');
                })
                ->with('usulan','laporankemajuan','laporanakhir') // Pastikan relasi 'usulan' didefinisikan di PenilaianReviewer
                ->get();
        
            // Jika tidak ada penilaian yang ditemukan, redirect untuk lengkapi profil
            if ($notifreview->isEmpty()) {
                return redirect()->route('profile.edit', ['id' => $user->id])->with('message', 'Harap lengkapi profil dosen Anda.');
            }

        
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
}
