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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


Route::resource('profile', ProfileController::class);
Route::get('profile/{id}/edit', [ProfileController::class, 'edit'])->name('profile.edit');

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
// Rute untuk Kepala LPPM dan Dosen (akses bersama)
     Route::group(['middleware' => ['role:Kepala LPPM|Dosen']], function () {
    // Rute terkait usulan
    Route::get('usulan/{jenis}', [UsulanController::class, 'show'])->name('usulan.show');
    Route::delete('usulan/{jenis}/{id}/hapus', [UsulanController::class, 'destroy'])->name('usulan.destroy');
    Route::resource('perbaikan-usulan', UsulanPerbaikanController::class);
    Route::get('perbaikan-usulan/{jenis}', [UsulanPerbaikanController::class, 'show'])->name('perbaikan-usulan.show');

    Route::get('/perbaikan-usulan/{usulan}/detail-revisi', [UsulanPerbaikanController::class, 'detailRevisi'])->name('perbaikan-usulan.detail_revisi');
// Route untuk upload PDF perbaikan revisi
Route::put('/perbaikan-usulan/{penilaianReviewer}/upload', [UsulanPerbaikanController::class, 'uploadRevisi'])
    ->name('perbaikan-usulan.upload_revisi');
    Route::resource('laporan-kemajuan', LaporanKemajuanController::class);
    Route::get('laporan-kemajuan/{jenis}', [LaporanKemajuanController::class, 'show'])->name('laporan-kemajuan.show');

    Route::resource('laporan-akhir', LaporanAkhirController::class);
    Route::get('laporan-akhir/{jenis}', [LaporanAkhirController::class, 'show'])->name('laporan-akhir.show');
    
});

// Rute khusus untuk Kepala LPPM
Route::group(['middleware' => ['role:Kepala LPPM']], function () {
    Route::put('users/update', [UserController::class, 'update'])->name('users.update');
    Route::resource('users', UserController::class);
    Route::resource('kriteria-penilaian', KriteriaPenilaianController::class);
    Route::delete('/kriteria-penilaian/{id}', [KriteriaPenilaianController::class, 'destroy'])->name('kriteria-penilaian.destroy');
    Route::resource('indikator-penilaian', IndikatorPenilaianController::class);
    Route::post('/usulan/{jenis}/kirim', [UsulanController::class, 'kirim'])->name('usulan.kirim');


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
    Route::get('review-usulan-lihat/{id}', [PenilaianReviewerController::class, 'lihatReviewUsulan'])->name('review-usulan.lihat');

    
    // Review Laporan Kemajuan
    Route::get('review-laporan-kemajuan', [PenilaianReviewerController::class, 'indexReviewLaporanKemajuan'])->name('review-laporan-kemajuan.index');
    Route::post('review-laporan-kemajuan/{id}', [PenilaianReviewerController::class, 'storeReviewLaporanKemajuan'])->name('review-laporan-kemajuan.store');

    
    Route::resource('form-penilaian', FormPenilaianController::class);
    Route::get('form-penilaian/create/{usulan_id}', [FormPenilaianController::class, 'create'])->name('form-penilaian.input');
    Route::get('perbaikan-penilaian/{usulan_id}', [FormPenilaianController::class, 'perbaikan'])->name('perbaikan-penilaian.lihat');
    // Route untuk menangani update status perbaikan (menggunakan PUT atau PATCH)
    Route::put('perbaikan-penilaian/{id}/update', [FormPenilaianController::class, 'updateStatus'])->name('perbaikan-penilaian.update');    
    Route::resource('reviewer', ReviewerController::class);
});

















// // Route::resource('usulan', UsulanController::class);
// Route::resource('usulan-perbaikan', UsulanPerbaikanController::class);
// Route::resource('laporan-kemajuan', LaporanKemajuanController::class);
// Route::resource('laporan-akhir', LaporanAkhirController::class);
// Route::resource('dokumen-luaran', DokumenLuaranController::class);

// //kepala lppm


// Route::group(['middleware' => ['role:Kepala LPPM']], function () {


//     Route::resource('dosen', DosenController::class);
//     Route::resource('reviewer', ReviewerController::class);
//     Route::get('/get-dosen', [AnggotaDosenController::class, 'getDosen'])->name('get.dosen');
//     Route::delete('/dosen/{id}', [AnggotaDosenController::class, 'destroy'])->name('anggota-dosen.destroy');
//    
//     Route::resource('usulan-perbaikan', UsulanPerbaikanController::class);

// });

// Route::group(['middleware' => ['role:Dosen']], function () {

// Route::get('usulan/{jenis}', [App\Http\Controllers\UsulanController::class, 'index']);
// Route::get('usulan/{jenis}/create', [UsulanController::class, 'create'])->name('usulan.create'); // Form tambah usulan
// Route::post('usulan/{jenis}', [UsulanController::class, 'store'])->name('usulan.store'); // Proses tambah usulan
// Route::get('usulan/{jenis}/{id}/edit', [UsulanController::class, 'edit'])->name('usulan.edit'); // Form edit usulan
// Route::put('usulan/{jenis}/{id}', [UsulanController::class, 'update'])->name('usulan.update'); // Proses edit usulan
// Route::get('/usulan/{jenis}/{id}', [UsulanController::class, 'show'])->name('usulan.show');
// // Route untuk menyetujui usulan, ditambahkan parameter jenis
// Route::patch('/usulan/{jenis}/{usulan}/submit', [UsulanController::class, 'submit'])->name('usulan.submit');
// // Rute untuk menghapus usulan (soft delete)
// Route::delete('usulan/{jenis}/{id}', [UsulanController::class, 'destroy'])->name('usulan.destroy');

// Route::post('/anggota-dosen', [AnggotaDosenController::class, 'store'])->name('anggota-dosen.store');
// // Route untuk edit anggota dosen
// Route::put('/anggota-dosen/{anggota_dosen}', [AnggotaDosenController::class, 'update'])->name('anggota-dosen.update');
// // Route untuk hapus anggota dosen
// Route::delete('/anggota-dosen/{anggota_dosen}', [AnggotaDosenController::class, 'destroy'])->name('anggota-dosen.destroy');
// // Route untuk menyetujui anggota dosen berdasarkan usulan
// Route::patch('/anggota-dosen/{usulan_id}/{anggota_dosen}/approve', [AnggotaDosenController::class, 'approve'])->name('anggota-dosen.approve');

// // Route untuk menolak anggota dosen berdasarkan usulan
// Route::patch('/anggota-dosen/{usulan_id}/{anggota_dosen}/reject', [AnggotaDosenController::class, 'reject'])->name('anggota-dosen.reject');

// // Route untuk menghapus anggota dosen
// Route::delete('/anggota-dosen/{anggota_dosen}', [AnggotaDosenController::class, 'destroy'])->name('anggota-dosen.destroy');


// Route::post('/anggota-mahasiswa/store', [AnggotaMahasiswaController::class, 'store'])->name('anggota-mahasiswa.store');
// // Rute untuk menghapus data
// Route::delete('/anggota-mahasiswa/{id}', [AnggotaMahasiswaController::class, 'destroy'])->name('anggota-mahasiswa.destroy');


// });


 
