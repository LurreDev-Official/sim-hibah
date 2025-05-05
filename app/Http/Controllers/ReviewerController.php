<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reviewer;

class ReviewerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreReviewerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reviewer $reviewer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reviewer $reviewer)
    {
        // Mengembalikan view untuk form edit dengan data reviewer
        return view('reviewers.edit', compact('reviewer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reviewer $reviewer)
    {
        // Validasi data yang masuk
        $request->validate([
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:255',
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ]);

        // Update data reviewer
        $reviewer->update([
            'nidn' => $request->input('nidn'),
            'fakultas' => $request->input('fakultas'),
            'prodi' => $request->input('prodi'),
        ]);

        //update name di model users
        $user = $reviewer->user;
        $user->update([
            'name' => $request->input('name'),
        ]);


        // Redirect atau response setelah update berhasil
        return redirect()->back()->with('success', 'Data reviewer berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reviewer $reviewer)
    {
        //
    }
}
