<?php

namespace App\Http\Controllers;

use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    public function index()
    {
        $penyakit = Penyakit::orderBy('kode_penyakit')->paginate(10);

        return view('admin.penyakit.index', compact('penyakit'));
    }

    public function create()
    {
        return view('admin.penyakit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_penyakit' => ['required', 'string', 'max:50', 'regex:/^[A-Z][0-9]{2}(\.[A-Z0-9]{1,4})?$/i', 'unique:penyakit,kode_penyakit'],
            'nama_penyakit' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'kode_penyakit.regex' => 'Kode harus menggunakan format ICD-10, contoh A01.0.',
        ]);

        Penyakit::create($validated);

        return redirect()->route('penyakit.index')->with('success', 'Penyakit berhasil ditambahkan.');
    }

    public function show(Penyakit $penyakit)
    {
        return redirect()->route('penyakit.edit', $penyakit);
    }

    public function edit(Penyakit $penyakit)
    {
        return view('admin.penyakit.edit', compact('penyakit'));
    }

    public function update(Request $request, Penyakit $penyakit)
    {
        $validated = $request->validate([
            'kode_penyakit' => ['required', 'string', 'max:50', 'regex:/^[A-Z][0-9]{2}(\.[A-Z0-9]{1,4})?$/i', 'unique:penyakit,kode_penyakit,' . $penyakit->id_penyakit . ',id_penyakit'],
            'nama_penyakit' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'kode_penyakit.regex' => 'Kode harus menggunakan format ICD-10, contoh A01.0.',
        ]);

        $penyakit->update($validated);

        return redirect()->route('penyakit.index')->with('success', 'Penyakit berhasil diperbarui.');
    }

    public function destroy(Penyakit $penyakit)
    {
        $penyakit->delete();

        return redirect()->route('penyakit.index')->with('success', 'Penyakit berhasil dihapus.');
    }
}
