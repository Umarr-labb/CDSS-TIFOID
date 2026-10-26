<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function index()
    {
        $gejala = Gejala::orderBy('kode_gejala')->paginate(10);

        return view('admin.gejala.index', compact('gejala'));
    }

    public function create()
    {
        return view('admin.gejala.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_gejala' => ['required', 'string', 'max:50', 'unique:gejala,kode_gejala'],
            'nama_gejala' => ['required', 'string', 'max:255'],
            'jenis' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Gejala::create($validated);

        return redirect()->route('gejala.index')->with('success', 'Gejala berhasil ditambahkan.');
    }

    public function show(Gejala $gejala)
    {
        return redirect()->route('gejala.edit', $gejala);
    }

    public function edit(Gejala $gejala)
    {
        return view('admin.gejala.edit', compact('gejala'));
    }

    public function update(Request $request, Gejala $gejala)
    {
        $validated = $request->validate([
            'kode_gejala' => ['required', 'string', 'max:50', 'unique:gejala,kode_gejala,' . $gejala->id_gejala . ',id_gejala'],
            'nama_gejala' => ['required', 'string', 'max:255'],
            'jenis' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $gejala->update($validated);

        return redirect()->route('gejala.index')->with('success', 'Gejala berhasil diperbarui.');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();

        return redirect()->route('gejala.index')->with('success', 'Gejala berhasil dihapus.');
    }
}
