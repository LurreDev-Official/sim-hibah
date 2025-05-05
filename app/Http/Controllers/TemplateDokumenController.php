<?php
namespace App\Http\Controllers;

use App\Models\TemplateDokumen;
use Illuminate\Http\Request;

class TemplateDokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = TemplateDokumen::all();
        return view('template_dokumen.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('template_dokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'proses' => 'required|string|max:255', // Updated field name
            'skema' => 'required|string|max:255', // Updated field name
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handling file upload
        $filePath = $request->file('file')->store('templates', 'public');

        // Creating the new TemplateDokumen
        TemplateDokumen::create([
            'nama' => $request->nama,
            'proses' => $request->proses, // Using the correct field
            'skema' => $request->skema,   // Using the correct field
            'file' => $filePath,
        ]);

        return redirect()->route('template-dokumen.index')->with('success', 'Template Dokumen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TemplateDokumen $templateDokumen)
    {
        return view('template_dokumen.show', compact('templateDokumen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemplateDokumen $templateDokumen)
    {
        return view('template_dokumen.edit', compact('templateDokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TemplateDokumen $templateDokumen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'proses' => 'required|string|max:255', // Updated field name
            'skema' => 'required|string|max:255', // Updated field name
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Handling file update
        if ($request->hasFile('file')) {
            // Delete old file if exists (optional but recommended for clean-up)
            if ($templateDokumen->file && file_exists(storage_path('app/public/' . $templateDokumen->file))) {
                unlink(storage_path('app/public/' . $templateDokumen->file));
            }

            // Store the new file
            $filePath = $request->file('file')->store('templates', 'public');
            $templateDokumen->file = $filePath;
        }

        // Update the rest of the fields
        $templateDokumen->nama = $request->nama;
        $templateDokumen->proses = $request->proses; // Correct field
        $templateDokumen->skema = $request->skema;   // Correct field
        $templateDokumen->save();

        return redirect()->route('template-dokumen.index')->with('success', 'Template Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Delete file before removing record
        // if ($templateDokumen->file && file_exists(storage_path('app/public/' . $templateDokumen->file))) {
        //     unlink(storage_path('app/public/' . $templateDokumen->file));
        // }
        $templateDokumen = TemplateDokumen::findOrFail($id);
        $templateDokumen->delete();
        return redirect()->route('template-dokumen.index')->with('success', 'Template Dokumen berhasil dihapus.');
    }
}
