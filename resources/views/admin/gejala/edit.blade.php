@extends('layouts.app')

@section('title', 'Edit Gejala')
@section('page-title', 'Edit Gejala')
@section('page-subtitle', 'Perbarui data gejala diagnosis tifoid')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-pencil-square text-primary"></i> Edit Gejala</h5>
    </div>

    <form action="{{ route('gejala.update', $gejala) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="kode_gejala" class="form-label">Kode Gejala</label>
                <input type="text" name="kode_gejala" id="kode_gejala" class="form-control @error('kode_gejala') is-invalid @enderror" value="{{ old('kode_gejala', $gejala->kode_gejala) }}" required>
                @error('kode_gejala')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="jenis" class="form-label">Jenis</label>
                <input type="text" name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror" value="{{ old('jenis', $gejala->jenis) }}" placeholder="Contoh: Umum, Pencernaan">
                @error('jenis')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="nama_gejala" class="form-label">Nama Gejala</label>
                <input type="text" name="nama_gejala" id="nama_gejala" class="form-control @error('nama_gejala') is-invalid @enderror" value="{{ old('nama_gejala', $gejala->nama_gejala) }}" required>
                @error('nama_gejala')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="4" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $gejala->keterangan) }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Perbarui Gejala</button>
            <a href="{{ route('gejala.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
