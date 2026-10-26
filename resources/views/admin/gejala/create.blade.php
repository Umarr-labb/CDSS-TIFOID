@extends('layouts.app')

@section('title', 'Tambah Gejala')
@section('page-title', 'Tambah Gejala')
@section('page-subtitle', 'Masukkan data gejala baru untuk sistem CDSS')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-clipboard-plus-fill text-primary"></i> Form Gejala Baru</h5>
    </div>

    <form action="{{ route('gejala.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label for="kode_gejala" class="form-label">Kode Gejala</label>
                <input type="text" name="kode_gejala" id="kode_gejala" class="form-control @error('kode_gejala') is-invalid @enderror" value="{{ old('kode_gejala') }}" required>
                @error('kode_gejala')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="jenis" class="form-label">Jenis</label>
                <input type="text" name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" value="{{ old('jenis') }}" placeholder="Contoh: Umum, Pencernaan">
                @error('jenis')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="nama_gejala" class="form-label">Nama Gejala</label>
                <input type="text" name="nama_gejala" id="nama_gejala" class="form-control @error('nama_gejala') is-invalid @enderror" value="{{ old('nama_gejala') }}" required>
                @error('nama_gejala')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Simpan Gejala</button>
            <a href="{{ route('gejala.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
