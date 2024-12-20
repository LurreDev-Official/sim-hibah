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
    public function update(Request $request)
{
    // Validasi data yang diterima dari form
    $request->validate([
        'user_id' => 'required|exists:users,id', // Pastikan user_id ada di tabel users
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $request->user_id, // Pastikan email unik kecuali email user ini
        'password' => 'nullable|min:6|confirmed' // Password opsional, tetapi jika diisi harus minimal 6 karakter dan cocok dengan konfirmasi
    ]);

    // Ambil user berdasarkan user_id
    $user = User::findOrFail($request->user_id);

    // Update informasi pengguna
    $user->name = $request->name;
    $user->email = $request->email;

    // Jika ada password yang diisi, perbarui password
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Simpan perubahan ke database
    $user->save();
 // Tambahkan notifikasi sukses ke session
 return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
