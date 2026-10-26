@extends('layouts.app')

@section('title', 'Tambah Penyakit')
@section('page-title', 'Tambah Penyakit')
@section('page-subtitle', 'Masukkan data penyakit baru untuk sistem CDSS')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-plus-circle-fill text-primary"></i> Form Penyakit Baru</h5>
    </div>

    <form action="{{ route('penyakit.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label for="kode_penyakit" class="form-label">Kode Internasional (ICD-10)</label>
                <input type="text" name="kode_penyakit" id="kode_penyakit" class="form-control @error('kode_penyakit') is-invalid @enderror" value="{{ old('kode_penyakit') }}" placeholder="Contoh: A01.0" aria-describedby="kode-penyakit-help" required>
                <div id="kode-penyakit-help" class="form-text">Gunakan format kode ICD-10, misalnya A01.0.</div>
                @error('kode_penyakit')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="nama_penyakit" class="form-label">Nama Penyakit</label>
                <input type="text" name="nama_penyakit" id="nama_penyakit" class="form-control @error('nama_penyakit') is-invalid @enderror" value="{{ old('nama_penyakit') }}" required>
                @error('nama_penyakit')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Simpan Penyakit</button>
            <a href="{{ route('penyakit.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
