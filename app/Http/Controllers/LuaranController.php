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
use App\Models\LaporanAkhir;
use App\Models\LaporanKemajuan;

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
    public function create($id)
{
    $usulan = Usulan::findOrFail($id); // More efficient than where()->first()
    $luarans = Luaran::where('usulan_id', $id)->pluck('jenis_luaran')->toArray(); // Get existing jenis_luaran directly
   
    $jenisLuarans = [
        'Laporan akhir penelitian',
        'Artikel ilmiah di jurnal terakreditasi minimal SINTA 3 atau SINTA 4',
        'Artikel ilmiah di prosiding SAINSTEKNOPAK'
    ];
    
    // Filter the jenisLuarans that do not exist in the database
    $missingLuarans = array_diff($jenisLuarans, $luarans);

    // Check and insert only the missing types
    foreach ($missingLuarans as $jenisLuaran) {
        // Check if a Luaran with this type and usulan_id already exists
        $existingLuaran = Luaran::where('usulan_id', $usulan->id)
                                ->where('type', $jenisLuaran)
                                ->first();
        // If it doesn't exist, create a new Luaran
        if (!$existingLuaran) {
            Luaran::create([
                'laporankemajuan_id'=> 0,
                'laporanakhir_id'=> 0,
                'usulan_id' => $usulan->id,
                'jenis_luaran' => 'wajib',  // Default value
                'judul' => 0,  // Default value
                'type' => $jenisLuaran,   // Default value
                'url' => 0,    // Default value
                'status' => 'belum terpenuhi',  // Default value
                'file_loa' => 0, // Default value
            ]);
        }
    }
    // Retrieve the most recent 'luaran' data
    $luarans = Luaran::where('usulan_id', $id)->get();
    
    $jenis = $usulan->jenis_skema;
    return view('luaran.create', compact('luarans', 'usulan', 'jenis'));
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'usulan_id' => 'required|exists:usulans,id', // Ensure usulan_id exists in the usulans table
        'judul' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'url' => 'required|url',
        'file_loa' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Make file_loa optional
    ]);

    try {
        // Initialize filePath to null
        $filePath = null;

        // Handle file upload if a file is provided
        if ($request->hasFile('file_loa')) {
            $file = $request->file('file_loa');
            
            // Generate a unique filename
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            
            // Store the file in the 'luaran' directory within the 'public' disk
            $filePath = $file->storeAs('luaran', $filename, 'public');
        }

        // Retrieve related IDs
        $laporankemajuan_id = LaporanKemajuan::where('usulan_id', $request->usulan_id)->first()->id;
        $laporanakhir_id = LaporanAkhir::where('usulan_id', $request->usulan_id)->first()->id;
        // Create a new Luaran record
        Luaran::create([
            'usulan_id' => $request->usulan_id,
            'laporankemajuan_id' => $laporankemajuan_id,
            'laporanakhir_id' => $laporanakhir_id,
            'jenis_luaran' => 'tambahan',  // Default value
            'judul' => $request->judul,
            'type' => $request->type,
            'url' => $request->url,
            'status' => 'terpenuhi',  // Default value
            'file_loa' => $filePath, // This will be null if no file was uploaded
        ]);

        // Redirect with success messagew
        return redirect()->back()->with('success', 'Berhasil di tambahkan');

    } catch (\Exception $e) {
        // Redirect back with error message
        return redirect()->back()->with('error', 'An error occurred while creating the Luaran: ' . $e->getMessage());
    }
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
                // dd($usulans);
                                 return view('luaran.index', compact('usulans', 'jenis'));
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

        // Store the new file and update the file_loa path
        $luaran->file_loa = $request->file('file_loa')->store('luaran', 'public'); // Store in 'luaran' directory
    }

    $luaran->save(); // Save the updated record

    return redirect()->back()->with('success', 'Luaran updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the Luaran record by ID
        $luaran = Luaran::findOrFail($id);
    
        // Delete the file if it exists
        if ($luaran->file_loa) {
            Storage::disk('public')->delete($luaran->file_loa);
        }
    
        // Delete the Luaran record
        $luaran->delete();
    
        return redirect()->back()->with('success', 'Luaran deleted successfully.');
    }

    

    public function updatestatus(Request $request, $id)
{
    // Validasi data
    $request->validate([
        'status' => 'required',
    ]);
    // dd($request->all());

    // Cari luaran berdasarkan ID
    $luaran = Luaran::findOrFail($id);

    // Perbarui status
    $luaran->status = $request->input('status');
    $luaran->save();

    // Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Status berhasil diperbarui menjadi: ' . ucfirst($luaran->status));
}



}