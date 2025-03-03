<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsulanController;
use App\Http\Controllers\AnggotaDosenController;
use App\Http\Controllers\AnggotaMahasiswaController;
use App\Http\Controllers\PenilaianReviewerController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\KriteriaPenilaianController;
use App\Http\Controllers\IndikatorPenilaianController;
use App\Http\Controllers\FormPenilaianController;
use App\Http\Controllers\UsulanPerbaikanController;
use App\Http\Controllers\LaporanKemajuanController;
use App\Http\Controllers\LaporanAkhirController;
use App\Http\Controllers\LuaranController;
use App\Http\Controllers\TemplateDokumenController;
use App\Http\Controllers\SintaScoreController;
use App\Http\Controllers\PeriodeController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/google/redirect', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/', function () {
    return view('auth.login');
});

//login
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


Route::resource('profile', ProfileController::class);
Route::get('profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::get('get-prodi/{fakultas_id}', [ProfileController::class, 'getProdiByFakultas'])->name('get-prodi');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');


// Rute untuk Kepala LPPM dan Dosen (akses bersama)
     Route::group(['middleware' => ['role:Kepala LPPM|Dosen|Admin']], function () {

    Route::resource('template-dokumen', TemplateDokumenController::class);
    // Rute terkait usulan
    Route::get('usulan/{jenis}', [UsulanController::class, 'show'])->name('usulan.show');
    Route::delete('usulan/{jenis}/{id}/hapus', [UsulanController::class, 'destroy'])->name('usulan.destroy');

    Route::put('usulan/{id}/update-status', [UsulanController::class, 'updateStatus'])->name('usulan.updateStatus');
    Route::get('usulan/{id}/cetak-bukti-acc', [UsulanController::class, 'cetakBuktiACC'])->name('usulan.cetakBuktiACC');
    //export excel usulan
    Route::get('usulan/{jenis}/export', [UsulanController::class, 'export'])->name('usulan.export');
    Route::get('luaran/{jenis}/export', [LuaranController::class, 'export'])->name('luaran.export');

    Route::resource('perbaikan-usulan', UsulanPerbaikanController::class);

    Route::get('perbaikan-usulan/{jenis}', [UsulanPerbaikanController::class, 'show'])->name('perbaikan-usulan.show');

    Route::get('/perbaikan-usulan/{usulan}/detail-revisi', [UsulanPerbaikanController::class, 'detailRevisi'])->name('perbaikan-usulan.detail_revisi');
// Route untuk upload PDF perbaikan revisi
Route::put('/perbaikan-usulan/{penilaianReviewer}/upload', [UsulanPerbaikanController::class, 'uploadRevisi'])
    ->name('perbaikan-usulan.upload_revisi');
    
    Route::resource('laporan-kemajuan', LaporanKemajuanController::class);
    Route::get('laporan-kemajuan/{jenis}', [LaporanKemajuanController::class, 'show'])->name('laporan-kemajuan.show');
    Route::get('laporan-kemajuan/create/{jenis?}', [LaporanKemajuanController::class, 'create'])->name('laporan-kemajuan.create');
// Route for deleting laporan kemajuan with only id parameter
Route::delete('laporan-kemajuan/{id}', [LaporanKemajuanController::class, 'destroy'])->name('laporan-kemajuan.destroy');

Route::put('laporan-kemajuan/{id}/update-status', [LaporanKemajuanController::class, 'updateStatus'])->name('laporan-kemajuan.updateStatus');
Route::get('laporan-kemajuan/{id}/cetak-bukti-acc', [LaporanKemajuanController::class, 'cetakBuktiACC'])->name('laporan-kemajuan.cetakBuktiACC');

Route::get('laporan-kemajuan/{jenis}/export', [LaporanKemajuanController::class, 'export'])->name('laporan-kemajuan.export');

    Route::resource('review-laporan-akhir', LaporanAkhirController::class);
    Route::resource('laporan-akhir', LaporanAkhirController::class);
    Route::get('laporan-akhir/{jenis}', [LaporanAkhirController::class, 'show'])->name('laporan-akhir.show');
    Route::get('laporan-akhir/create/{jenis?}', [LaporanAkhirController::class, 'create'])->name('laporan-akhir.create');
    Route::delete('laporan-akhir/{id}', [LaporanAkhirController::class, 'destroy'])->name('laporan-akhir.destroy');
    Route::put('laporan-akhir/{id}/update-status', [LaporanAkhirController::class, 'updateStatus'])->name('laporan-akhir.updateStatus');
    Route::get('laporan-akhir/{id}/cetak-bukti-acc', [LaporanAkhirController::class, 'cetakBuktiACC'])->name('laporan-akhir.cetakBuktiACC');
    Route::get('report/{jenis}', [LaporanAkhirController::class, 'report'])->name('report.lihat');
    Route::post('/report/filter', [LaporanAkhirController::class, 'filterOrExport'])->name('report.filter');

    //luaran
    Route::resource('luaran', LuaranController::class);
    Route::get('luaran/{jenis}', [LuaranController::class, 'show'])->name('luaran.show');
    Route::get('luaran/create/{jenis?}', [LuaranController::class, 'create'])->name('luaran.create');
    Route::post('luaran/status/{id}', [LuaranController::class, 'updatestatus'])->name('luaran.status');
    Route::delete('luaran/{id}', [LuaranController::class, 'destroy'])->name('luaran.destroy');


    Route::get('/sinta-score/{nidn}', [SintaScoreController::class, 'getSintaScore'])->name('sinta.score');


    
});

// Rute khusus untuk Kepala LPPM
Route::group(['middleware' => ['role:Kepala LPPM|Admin']], function () {
    Route::put('users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::resource('users', UserController::class);
    Route::resource('kriteria-penilaian', KriteriaPenilaianController::class);
    Route::delete('/kriteria-penilaian/{id}', [KriteriaPenilaianController::class, 'destroy'])->name('kriteria-penilaian.destroy');
    Route::resource('indikator-penilaian', IndikatorPenilaianController::class);
    Route::post('/usulan/{jenis}/kirim', [UsulanController::class, 'kirim'])->name('usulan.kirim');
    Route::post('/laporan-kemajuan/{jenis}/kirim', [LaporanKemajuanController::class, 'kirim'])->name('laporan-kemajuan.kirim');
    Route::post('/laporan-akhir/{jenis}/kirim', [LaporanAkhirController::class, 'kirim'])->name('laporan-akhir.kirim');
   
    Route::get('/laporan-akhir/{jenis}/export', [LaporanAkhirController::class, 'export'])->name('laporan-akhir.export');
   
    //sinta-score
    Route::get('sinta-score', [SintaScoreController::class, 'index'])->name('sinta-score.index');
    Route::post('sinta-scores/import', [SintaScoreController::class, 'import'])->name('sinta-scores.import');
    Route::get('sinta-scores/export', [SintaScoreController::class, 'export'])->name('sinta-scores.export');

    Route::resource('periodes', PeriodeController::class);
    Route::get('periodes/{id}/delete', [PeriodeController::class, 'delete'])->name('periodes.delete');
    //plot
    Route::get('plot', [UsulanController::class, 'plot'])->name('plot.index');
    Route::get('/filter-dosens-by-year', [UsulanController::class, 'filterByYear'])->name('filter.dosens.by.year');
    Route::get('per-fakultas', [UsulanController::class, 'grafikPerFakultas'])->name('grafik-per-fakultas.index');
Route::get('per-prodi', [UsulanController::class, 'grafikPerProdi'])->name('grafik-prodi.index');
    Route::get('laporan-hitungan-usulan', [UsulanController::class, 'laporanHitunganUsulan'])->name('laporan-hitungan-usulan.index');
    Route::post('laporan-hitungan-usulan', [UsulanController::class, 'laporanHitunganUsulan'])->name('laporan-hitungan-usulan.filter');

    // Route::get('setting-lembar-pengesahan', [UsulanController::class, 'settingLembarPengesahan'])->name('setting-lembar-pengesahan.index');
});

// Rute khusus untuk Dosen
Route::group(['middleware' => ['role:Dosen']], function () {
    Route::resource('usulan', UsulanController::class);
    Route::get('usulan/{jenis}/create', [UsulanController::class, 'create'])->name('usulan.create'); // Form tambah usulan
    Route::post('usulan/{jenis}', [UsulanController::class, 'store'])->name('usulan.store'); // Proses tambah usulan
    Route::get('usulan/{jenis}/{id}/edit', [UsulanController::class, 'edit'])->name('usulan.edit'); // Form edit usulan
    Route::put('usulan/{jenis}/{id}', [UsulanController::class, 'update'])->name('usulan.update'); // Proses edit usulan
    Route::patch('/usulan/{jenis}/{usulan}/submit', [UsulanController::class, 'submit'])->name('usulan.submit');
    Route::get('detail-usulan/{jenis}/{id}', [UsulanController::class, 'detail'])->name('usulan.detail');
    // Route for Perbaiki Revisi
    Route::get('detail-usulan/{jenis}/{id}/perbaiki-revisi', [UsulanController::class, 'perbaikiRevisi'])->name('usulan.perbaikiRevisi');
    // Route for saving Perbaikan Revisi
    Route::post('detail-usulan/{id}/perbaiki-revisi', [UsulanController::class, 'simpanPerbaikan'])->name('usulan.simpanPerbaikan');

    Route::get('detail-laporan-kemajuan/{jenis}/{id}/perbaiki-revisi', [LaporanKemajuanController::class,'perbaikiRevisi'])->name('laporan-kemajuan.perbaikiRevisi');
    Route::post('detail-laporan-kemajuan/{id}/perbaiki-revisi', [LaporanKemajuanController::class,'simpanPerbaikan'])->name('laporan-kemajuan.simpanPerbaikan');

    Route::get('detail-laporan-akhir/{jenis}/{id}/perbaiki-revisi', [LaporanAkhirController::class,'perbaikiRevisi'])->name('laporan-akhir.perbaikiRevisi');
    Route::post('detail-laporan-akhir/{id}/perbaiki-revisi', [LaporanAkhirController::class,'simpanPerbaikan'])->name('laporan-akhir.simpanPerbaikan');

    // Rute untuk anggota dosen
    Route::post('/anggota-dosen', [AnggotaDosenController::class, 'store'])->name('anggota-dosen.store');
    Route::put('/anggota-dosen/{anggota_dosen}', [AnggotaDosenController::class, 'update'])->name('anggota-dosen.update');
    Route::delete('/anggota-dosen/{anggota_dosen}', [AnggotaDosenController::class, 'destroy'])->name('anggota-dosen.destroy');
    Route::patch('/anggota-dosen/{usulan_id}/{anggota_dosen}/approve', [AnggotaDosenController::class, 'approve'])->name('anggota-dosen.approve');
    Route::patch('/anggota-dosen/{usulan_id}/{anggota_dosen}/reject', [AnggotaDosenController::class, 'reject'])->name('anggota-dosen.reject');

    // Rute untuk anggota mahasiswa
    Route::post('/anggota-mahasiswa/store', [AnggotaMahasiswaController::class, 'store'])->name('anggota-mahasiswa.store');
    Route::delete('/anggota-mahasiswa/{id}', [AnggotaMahasiswaController::class, 'destroy'])->name('anggota-mahasiswa.destroy');


});

// Reviewer Routes
Route::group(['middleware' => ['role:Reviewer']], function () {
    Route::resource('penilaian-usulan', PenilaianReviewerController::class);
    // Penilaian Usulan
    Route::get('penilaian-usulan', [PenilaianReviewerController::class, 'indexPenilaianUsulan'])->name('penilaian-usulan.index');
    Route::post('penilaian-usulan/{id}', [PenilaianReviewerController::class, 'storePenilaianUsulan'])->name('penilaian-usulan.store');

    // Review Usulan
    Route::get('review-usulan', [PenilaianReviewerController::class, 'indexReviewUsulan'])->name('review-usulan.index');
    Route::post('review-usulan/{id}', [PenilaianReviewerController::class, 'storeReviewUsulan'])->name('review-usulan.store');
    Route::put('review-usulan/{id}/update', [PenilaianReviewerController::class, 'updateStatus'])->name('review-usulan.updateStatus');
    Route::get('review-usulan-lihat/{id}', [PenilaianReviewerController::class, 'lihatReviewUsulan'])->name('review-usulan.lihat');

    // Review Laporan Kemajuan
    Route::get('review-laporan-kemajuan', [PenilaianReviewerController::class, 'indexReviewLaporanKemajuan'])->name('review-laporan-kemajuan.index');
    Route::post('review-laporan-kemajuan/{id}', [PenilaianReviewerController::class, 'storeReviewLaporanKemajuan'])->name('review-laporan-kemajuan.store');
    Route::put('review-laporan-kemajuan/{id}/update', [PenilaianReviewerController::class, 'updateStatus'])->name('review-laporan-kemajuan.updateStatus');

    Route::get('review-laporan-kemajuan-lihat/{id}', [PenilaianReviewerController::class, 'lihatReviewLaporanKemajuan'])->name('review-laporan-kemajuan.lihat');

    Route::get('review-laporan-akhir', [PenilaianReviewerController::class, 'indexReviewLaporanAkhir'])->name('review-laporan-akhir.index');
    Route::post('review-laporan-akhir/{id}', [PenilaianReviewerController::class, 'storeReviewLaporanAkhir'])->name('review-laporan-akhir.store');
    Route::get('review-laporan-akhir-lihat/{id}', [PenilaianReviewerController::class, 'lihatReviewLaporanAkhir'])->name('review-laporan-akhir.lihat');
    Route::put('review-laporan-akhir/{id}/update', [PenilaianReviewerController::class, 'updateStatus'])->name('review-laporan-akhir.updateStatus');

    Route::resource('form-penilaian', FormPenilaianController::class);
    Route::get('form-penilaian/create/{id}', [FormPenilaianController::class, 'create'])->name('form-penilaian.input');
    Route::get('form-penilaian/laporan-kemajuan/{id}', [FormPenilaianController::class, 'createLaporanKemajuan'])->name('form-penilaian.laporan-kemajuan');
    Route::get('form-penilaian/laporan-akhir/{id}', [FormPenilaianController::class, 'createLaporanAkhir'])->name('form-penilaian.laporan-akhir');


    // Route untuk form penilaian Laporan Kemajuan
    Route::post('form-penilaian/laporan-kemajuan/{id}', [FormPenilaianController::class, 'storeLaporanKemajuan'])->name('form-penilaian.laporan-kemajuan.store');

    // Route untuk form penilaian Laporan Akhir
    Route::post('form-penilaian/laporan-akhir/{id}', [FormPenilaianController::class, 'storeLaporanAkhir'])->name('form-penilaian.laporan-akhir.store');



    Route::get('perbaikan-penilaian/{usulan_id}', [FormPenilaianController::class, 'perbaikan'])->name('perbaikan-penilaian.lihat');
    Route::resource('reviewer', ReviewerController::class);
});

