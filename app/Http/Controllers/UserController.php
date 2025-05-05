<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();
        return view('users.index', compact('data'));
    }

    public function bypassLogin(User $user)
{
    // Ensure only Kepala LPPM or Admin can bypass login
    if (!auth()->user()->hasRole(['Kepala LPPM', 'Admin'])) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access'
        ], 403);
    }

    // Log the bypass login attempt
    Log::channel('security')->info('Login bypassed', [
        'admin_id' => auth()->id(),
        'admin_name' => auth()->user()->name,
        'user_id' => $user->id,
        'user_email' => $user->email
    ]);

    // Forcefully log in the user
    Auth::login($user);

    // Return response
    return response()->json([
        'success' => true,
        'redirect' => route('dashboard'),
        'message' => 'Login berhasil dialihkan'
    ]);
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
    public function update(Request $request, $id)
    {
        // Ambil user berdasarkan ID
        $user = User::findOrFail($id);
    
        // Validasi data yang diterima dari form
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => [
                'min:8', // Minimal 8 karakter
            ],
            'user_id' => 'required|exists:users,id',
        ];
    
        // Validasi input
        $validatedData = $request->validate($validationRules);
    
        // Update nama dan email
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
    
        // Perbarui password jika disediakan
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
    
        // Simpan perubahan
        $user->save();
    
        // Redirect dengan pesan sukses
        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }
    

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
