<?php

namespace App\Http\Controllers;

use App\Models\UsulanPerbaikan;
use App\Models\PenilaianReviewer;

use Illuminate\Http\Request;

use App\Models\Dosen;
use App\Models\Usulan;
use Illuminate\Support\Facades\Auth;
class UsulanPerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();
    
        // Jika user memiliki peran 'Kepala LPPM'
        if ($user->hasRole('Kepala LPPM')) {
            // Ambil semua data UsulanPerbaikan
            $usulanPerbaikans = UsulanPerbaikan::all();
        } 
        // Jika user bukan Dosen (misalnya role lain)
        elseif (!$user->hasRole('Dosen')) {
            // Ambil data Usulan berdasarkan ketua_id yang sesuai
            $getusulanis = Usulan::where('ketua_dosen_id', $user->id)->get();
    
            // Ambil semua UsulanPerbaikan yang terkait dengan usulan_id yang ditemukan
            $usulanPerbaikans = UsulanPerbaikan::whereIn('usulan_id', $getusulanis->pluck('id'))->get();
        } 
        // Jika user adalah Dosen
        else {
            // Ambil ID user yang sedang login
            $getuser = $user->id;
    
            // Ambil data Dosen berdasarkan user_id
            $getdosen = Dosen::where('user_id', $getuser)->first();
    
            // Ambil data Usulan berdasarkan ketua_dosen_id
            $getusulanis = Usulan::where('ketua_dosen_id', $getdosen->id)->get();
    
            // Ambil semua UsulanPerbaikan yang terkait dengan usulan_id yang ditemukan
            $usulanPerbaikans = UsulanPerbaikan::whereIn('usulan_id', $getusulanis->pluck('id'))->get();
        }
    
        return view('usulan_perbaikan.index', compact('usulanPerbaikans'));
    }
    
    
    public function create()
    {
      
    }
    /**
     * Show the form for creating a new resource.
     */
    public function detailRevisi($usulan_id)
    {
        $usulan = Usulan::find($usulan_id); 
        $penilaian = PenilaianReviewer::with('reviewer','usulanPerbaikan', 'usulanPerbaikan.usulan')  // Eager load usulanPerbaikan dan usulan
                                ->where('usulan_id', $usulan_id)
                                ->get();
        return view('usulan_perbaikan.create', compact('usulan', 'penilaian'));
    }

      // Menangani Upload PDF Revisi untuk Usulan Perbaikan
      public function uploadRevisi(Request $request, $penilaianReviewerId)
      {
          // Validasi file yang di-upload
          $request->validate([
              'pdf_file' => 'required|mimes:pdf|max:10240', // Maksimum ukuran 10MB
          ]);
        //   dd($penilaianReviewerId);
          // Menemukan PenilaianReviewer berdasarkan ID
          $penilaianReviewer = PenilaianReviewer::findOrFail($penilaianReviewerId);
  
          // Menemukan UsulanPerbaikan terkait PenilaianReviewer
          $usulanPerbaikan = UsulanPerbaikan::where('penilaian_id', $penilaianReviewerId)->first();
  
          if (!$usulanPerbaikan) {
              return redirect()->route('perbaikan-usulan.detail_revisi', ['usulan' => $penilaianReviewer->usulan_id])
                               ->with('error', 'Usulan perbaikan tidak ditemukan.');
          }
  
          if ($request->hasFile('pdf_file')) {
            // Mengambil file yang di-upload
            $file = $request->file('pdf_file');
            
            // Mendapatkan nama file asli
            $originalFileName = $file->getClientOriginalName();
            
            // Menentukan nama baru untuk file (misalnya: nama_file_reviewerId.pdf)
            $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $penilaianReviewer->reviewer_id . '.pdf';
            
            // Menyimpan file dengan nama yang sesuai menggunakan store()
            $filePath = $file->storeAs('dokumen_usulan', $newFileName, 'public');  // Store the file in 'dokumen_usulan' directory on the public disk
        
            // Update path file dalam tabel UsulanPerbaikan
            $usulanPerbaikan->update([
                'dokumen_usulan' => $filePath,  // Menyimpan file ke kolom dokumen_usulan
                'status' => 'sudah diperbaiki',  // Menyimpan file ke kolom dokumen_usulan
            ]);
        
             
        }
  
          // Redirect kembali dengan pesan sukses
          return redirect()->route('perbaikan-usulan.detail_revisi', ['usulan' => $penilaianReviewer->usulan_id])
                           ->with('success', 'File revisi berhasil diunggah');
      }
  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'dokumen_usulan' => 'required|string|max:255',
            'penilaian_id' => 'required|exists:penilaian_reviewers,id',
            'status' => 'required|in:didanai,tidak didanai',
            'usulan_id' => 'required|exists:usulans,id',
        ]);

        // Menyimpan UsulanPerbaikan baru
        UsulanPerbaikan::create($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('usulan_perbaikan.index')->with('success', 'Usulan Perbaikan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        // Ambil user yang sedang login
        $user = Auth::user();
    
        // Jika user memiliki peran 'Kepala LPPM'
        if ($user->hasRole('Kepala LPPM')) {
            // Ambil semua data UsulanPerbaikan
            $usulanPerbaikans = UsulanPerbaikan::all();
        } 
        // Jika user bukan Dosen (misalnya role lain)
        elseif (!$user->hasRole('Dosen')) {
            // Ambil data Usulan berdasarkan ketua_id yang sesuai
            $getusulanis = Usulan::where('ketua_dosen_id', $user->id)->get();
    
            // Ambil semua UsulanPerbaikan yang terkait dengan usulan_id yang ditemukan
            $usulanPerbaikans = UsulanPerbaikan::whereIn('usulan_id', $getusulanis->pluck('id'))->get();
        } 
        // Jika user adalah Dosen
        else {
            // Ambil ID user yang sedang login
            $getuser = $user->id;
    
            // Ambil data Dosen berdasarkan user_id
            $getdosen = Dosen::where('user_id', $getuser)->first();
    
            // Ambil data Usulan berdasarkan ketua_dosen_id
            $getusulanis = Usulan::where('ketua_dosen_id', $getdosen->id)->get();
    
            // Ambil semua UsulanPerbaikan yang terkait dengan usulan_id yang ditemukan
            $usulanPerbaikans = UsulanPerbaikan::whereIn('usulan_id', $getusulanis->pluck('id'))->get();
        }
    
        return view('usulan_perbaikan.index', compact('usulanPerbaikans'));
   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsulanPerbaikan $usulanPerbaikan)
    {
        // Menampilkan form untuk mengedit UsulanPerbaikan
        return view('usulan_perbaikan.edit', compact('usulanPerbaikan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UsulanPerbaikan $usulanPerbaikan)
    {
        // Validasi input
        $request->validate([
            'dokumen_usulan' => 'required|string|max:255',
            'penilaian_id' => 'required|exists:penilaian_reviewers,id',
            'status' => 'required|in:didanai,tidak didanai',
            'usulan_id' => 'required|exists:usulans,id',
        ]);

        // Mengupdate UsulanPerbaikan
        $usulanPerbaikan->update($request->all());

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('usulan_perbaikan.index')->with('success', 'Usulan Perbaikan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UsulanPerbaikan $usulanPerbaikan)
    {
        // Menghapus UsulanPerbaikan
        $usulanPerbaikan->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('usulan_perbaikan.index')->with('success', 'Usulan Perbaikan berhasil dihapus.');
    }
}