<?php

namespace App\Http\Controllers;

use App\Models\Luaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Usulan;
use App\Models\Dosen;
use App\Models\AnggotaDosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LuaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $luarans = Luaran::all(); // Fetch all Luaran records
        return view('luaran.index', compact('luarans')); // Return the view with the data
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('luaran.create', compact('usulans','jenis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'url' => 'required|url',
            'file_loa' => 'required|file|mimes:pdf,doc,docx|max:2048', // Adjust file types and size as needed
        ]);

        // Handle file upload
        $filePath = $request->file('file_loa')->store('files', 'public');

        // Create a new Luaran record
        Luaran::create([
            'judul' => $request->judul,
            'type' => $request->type,
            'url' => $request->url,
            'file_loa' => $filePath,
        ]);

        return redirect()->route('luaran.index')->with('success', 'Luaran created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($jenis)
    {
        $user = auth()->user(); // Ambil data user yang sedang login
        if ($user->hasRole('Kepala LPPM')) {
            $usulans = Usulan::where('jenis_skema', $jenis)->get();
            return view('luaran.index', compact('usulans', 'jenis'));

        }
        else{
            $dosen = Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                // Ambil semua usulan_id yang terkait dengan dosen dari tabel AnggotaDosen
                $usulanIds = AnggotaDosen::where('dosen_id', $dosen->id)->pluck('usulan_id');
                // Ambil semua usulan berdasarkan usulan_id yang ditemukan di AnggotaDosen
                $usulans = Usulan::whereIn('id', $usulanIds)
                                 ->where('jenis_skema', $jenis)
                                 ->where('status', 'approved')->get();
                dd($usulans);
                                //  return view('luaran.index', compact('usulans', 'jenis'));
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Luaran $luaran)
    {
      
        $user = auth()->user(); // Get the currently authenticated user
        // Check if the user has the 'Dosen' role
        if ($user->hasRole('Dosen')) {
            // Filter Usulan based on ketua_dosen_id and optionally the 'jenis' if provided
            $usulans = Usulan::where('ketua_dosen_id', $user->dosen->id)
            ->when($jenis, function($query, $jenis) {
                return $query->where('jenis_skema', $jenis);  // Filtering by jenis
            })
            ->where('status', 'approved') // Adding the status filter after the initial condition
            ->get();
        
            return view('luaran.create', compact('usulans', 'jenis'));
        } else {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membuat laporan.');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Luaran $luaran)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'url' => 'required|url',
            'file_loa' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Optional file upload
        ]);

        // Update the Luaran record
        $luaran->judul = $request->judul;
        $luaran->type = $request->type;
        $luaran->url = $request->url;

        // Handle file upload if a new file is provided
        if ($request->hasFile('file_loa')) {
            // Delete the old file if it exists
            if ($luaran->file_loa) {
                Storage::disk('public')->delete($luaran->file_loa);
            }
            $luaran->file_loa = $request->file('file_loa')->store('files', 'public');
        }

        $luaran->save(); // Save the updated record

        return redirect()->route('luaran.index')->with('success', 'Luaran updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Luaran $luaran)
    {
        // Delete the file if it exists
        if ($luaran->file_loa) {
            Storage::disk('public')->delete($luaran->file_loa);
        }

        $luaran->delete(); // Delete the Luaran record

        return redirect()->route('luaran.index')->with('success', 'Luaran deleted successfully.');
    }
}