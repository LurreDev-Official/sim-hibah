<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::paginate(10);
        return view('users.index', compact('data'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);
    
        // Kembalikan data user dalam format JSON untuk digunakan di form edit (AJAX)
        return response()->json($user);
    }
public function update(Request $request,$id)
{
    // Validasi data yang diterima dari form
    $request->validate([
        'password' => 'required' // Password opsional, tetapi jika diisi harus minimal 6 karakter dan cocok dengan konfirmasi
    ]);
    try {
        // Ambil user berdasarkan user_id
        $user = User::findOrFail($id);

        // Jika ada password yang diisi, perbarui password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan ke database
        $user->save();

        // Tambahkan notifikasi sukses ke session
        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    } catch (\Exception $e) {
        // Tambahkan notifikasi error ke session jika terjadi kesalahan
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data pengguna: ' . $e->getMessage());
    }
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
