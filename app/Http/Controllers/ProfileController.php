<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->hasRole('Kepala LPPM')) {
            return view('profile.kepala_lppm_edit', compact('user'));
        }
         elseif (Auth::user()->hasRole('Dosen')) {
            // Cek apakah data Dosen sudah ada atau buat jika tidak ada
            $dosen = Dosen::firstOrCreate(
                ['user_id' => $user->id], // Kondisi pencarian
                [
                    'nidn' => 0, // Default value jika tidak ditemukan
                    'kuota_proposal' => 6,
                    'jumlah_proposal' => 0,
                    'fakultas' => '-',
                    'prodi' => '-',
                    'score_sinta' => 0,
                    'status' => 'anggota'
                ]
            );
        
            // Pass both User and Dosen data to the view
            return view('profile.dosen_edit', compact('user', 'dosen'));
        }
        elseif (Auth::user()->hasRole('Reviewer')) {
            $reviewer = Reviewer::firstOrCreate(
                ['user_id' => Auth::id()], // Kondisi untuk mencari
                [
                    'nidn' => '0', // Nilai default untuk kolom nidn
                    'fakultas' => '', // Nilai default untuk kolom fakultas
                    'prodi' => '' // Nilai default untuk kolom prodi
                ]
            );
            return view('profile.reviewer_edit', compact('user','reviewer'));
        }

        return redirect()->url('/')->with('success', 'updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Handle the validation and updating of the user here
        // Example for Kepala LPPM
        if (Auth::user()->hasRole('Kepala LPPM')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|min:8',
            ]);

            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
        }
        if (Auth::user()->hasRole('Dosen')) {
            // Check if Dosen exists by user_id
                $dosen = Dosen::find($id);
                if ($dosen) {
                    $dosen->nidn = $request->nidn;
                    $dosen->fakultas = $request->fakultas;
                    $dosen->prodi = $request->prodi;
                    $dosen->score_sinta = $request->score_sinta;
                    $dosen->save();

                    return redirect()->route('dashboard')->with('success', 'Dosen updated successfully.');

                }  
        }

        return redirect()->route('dashboard')->with('success', 'Dosen updated successfully.');

    }
}
