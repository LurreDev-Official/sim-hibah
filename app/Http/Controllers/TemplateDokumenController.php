<?php

namespace App\Http\Controllers;

use App\Models\TemplateDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TemplateDokumenController extends Controller
{
    public function index()
    {
        $templates = TemplateDokumen::all();
        return view('template_dokumen.index', compact('templates'));
    }

    public function create()
    {
        return view('template_dokumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'proses' => 'required|string|max:255',
            'skema' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $file = $request->file('file');

        // Ganti karakter non-alfanumerik agar aman sebagai nama file
        $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->nama);
        $filename = $cleanName . '-' . time() . '.' . $file->getClientOriginalExtension();

        $filePath = $file->storeAs('templates', $filename, 'public');

        TemplateDokumen::create([
            'nama' => $request->nama,
            'proses' => $request->proses,
            'skema' => $request->skema,
            'file' => $filePath,
        ]);

        return redirect()->route('template-dokumen.index')->with('success', 'Template Dokumen berhasil ditambahkan.');
    }

    public function show(TemplateDokumen $templateDokumen)
    {
        return view('template_dokumen.show', compact('templateDokumen'));
    }

    public function edit(TemplateDokumen $templateDokumen)
    {
        return view('template_dokumen.edit', compact('templateDokumen'));
    }

    public function update(Request $request, TemplateDokumen $templateDokumen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'proses' => 'required|string|max:255',
            'skema' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Cek duplikat berdasarkan kombinasi unik
        $duplicate = TemplateDokumen::where('nama', $request->nama)
            ->where('proses', $request->proses)
            ->where('skema', $request->skema)
            ->where('id', '!=', $templateDokumen->id)
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'nama' => 'Data dengan kombinasi nama, proses, dan skema ini sudah ada.',
            ]);
        }

        if ($request->hasFile('file')) {
            if ($templateDokumen->file && file_exists(storage_path('app/public/' . $templateDokumen->file))) {
                unlink(storage_path('app/public/' . $templateDokumen->file));
            }

            $file = $request->file('file');
            $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->nama);
            $filename = $cleanName . '-' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('templates', $filename, 'public');
            $templateDokumen->file = $filePath;
        }

        $templateDokumen->nama = $request->nama;
        $templateDokumen->proses = $request->proses;
        $templateDokumen->skema = $request->skema;
        $templateDokumen->save();

        return redirect()->route('template-dokumen.index')->with('success', 'Template Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $templateDokumen = TemplateDokumen::findOrFail($id);

        // Hapus file dari storage
        if ($templateDokumen->file && file_exists(storage_path('app/public/' . $templateDokumen->file))) {
            unlink(storage_path('app/public/' . $templateDokumen->file));
        }

        $templateDokumen->delete();

        return redirect()->route('template-dokumen.index')->with('success', 'Template Dokumen berhasil dihapus.');
    }
}
