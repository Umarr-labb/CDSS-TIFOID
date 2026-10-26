@extends('layouts.app')

@section('title', 'Input Data Pasien')
@section('page-title', 'Konsultasi Baru')
@section('page-subtitle', 'Lengkapi informasi pasien untuk memulai konsultasi klinis')

@section('content')
<div class="workflow-steps mb-4">
    <span class="workflow-step active"><b>1</b> Data pasien</span>
    <span class="workflow-divider"></span>
    <span class="workflow-step"><b>2</b> Pilih gejala</span>
    <span class="workflow-divider"></span>
    <span class="workflow-step"><b>3</b> Hasil diagnosis</span>
</div>

<div class="panel patient-form-panel">
    <div class="panel-header">
        <div>
            <h5><i class="bi bi-person-vcard-fill text-primary me-1"></i> Informasi Pasien</h5>
            <small class="text-muted">Kolom bertanda <span class="text-danger">*</span> wajib diisi.</small>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle me-1"></i> Periksa kembali data pasien</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('konsultasi.storePasien') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="no_rm" class="form-label fw-semibold">Nomor Rekam Medis <span class="text-danger">*</span></label>
                <input type="text" id="no_rm" name="no_rm" class="form-control @error('no_rm') is-invalid @enderror" value="{{ old('no_rm') }}" placeholder="Contoh: RM-001" autocomplete="off" required>
                <div class="form-text">Gunakan nomor rekam medis atau nomor antrian pasien.</div>
                @error('no_rm')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="nama_pasien" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="nama_pasien" name="nama_pasien" class="form-control @error('nama_pasien') is-invalid @enderror" value="{{ old('nama_pasien') }}" placeholder="Masukkan nama lengkap pasien" autocomplete="name" required>
                @error('nama_pasien')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                    <option value="">Pilih jenis kelamin</option>
                    <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                </select>
                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="umur" class="form-label fw-semibold">Umur <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" id="umur" name="umur" class="form-control @error('umur') is-invalid @enderror" value="{{ old('umur') }}" placeholder="Contoh: 25" min="0" max="150" required>
                    <span class="input-group-text bg-white">tahun</span>
                </div>
                @error('umur')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="workflow-form-footer mt-4 pt-3 border-top">
            <span class="small text-muted"><i class="bi bi-shield-lock-fill text-primary me-1"></i> Informasi pasien digunakan untuk kebutuhan konsultasi.</span>
            <button type="submit" class="btn btn-primary text-white">
                Lanjut ke Pilih Gejala <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
</div>
@endsection
