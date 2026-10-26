@extends('layouts.app')

@section('title', 'Pilih Gejala')
@section('page-title', 'Pilih Gejala')
@section('page-subtitle', 'Tentukan tingkat keyakinan terhadap gejala yang dialami pasien')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <span class="badge rounded-pill text-bg-light border px-3 py-2">
        <i class="bi bi-person-fill text-primary me-1"></i>
        {{ $pasien->nama_pasien }} <span class="text-muted ms-1">· {{ $pasien->no_rm }}</span>
    </span>
</div>

<div class="workflow-steps mb-4">
    <span class="workflow-step complete"><b><i class="bi bi-check-lg"></i></b> Data pasien</span>
    <span class="workflow-divider"></span>
    <span class="workflow-step active"><b>2</b> Pilih gejala</span>
    <span class="workflow-divider"></span>
    <span class="workflow-step"><b>3</b> Hasil diagnosis</span>
</div>

<div class="panel">
    <div class="panel-header flex-wrap gap-3">
        <div>
            <h5><i class="bi bi-clipboard2-pulse-fill text-primary me-1"></i> Gejala yang Dialami</h5>
            <small class="text-muted">Pilih tingkat keyakinan untuk setiap gejala di bawah.</small>
        </div>
        <span class="badge rounded-pill text-bg-light border px-3 py-2 text-primary">
            <i class="bi bi-info-circle me-1"></i> Tidak yakin = tidak ada gejala
        </span>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">Periksa kembali jawaban gejala.</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alert alert-info py-2 small">
        <i class="bi bi-lightbulb-fill me-1"></i>
        Nilai keyakinan membantu sistem membandingkan gejala dengan basis aturan. Pilih <strong>Tidak yakin</strong> jika gejala tidak dialami pasien.
    </div>

    <form action="{{ route('konsultasi.proses', $pasien->id_pasien) }}" method="POST">
        @csrf
        @if($gejala->isNotEmpty())
            <div class="symptom-list">
                @foreach($gejala as $g)
                    @php($nilaiTerpilih = old('gejala.' . $g->id_gejala, $skala->first()->nilai_cf ?? 0))
                    <div class="symptom-item">
                        <div class="symptom-label">
                            <span class="symptom-icon"><i class="bi bi-activity"></i></span>
                            <span>
                                <strong>{{ $g->nama_gejala }}</strong>
                                <small>{{ $g->jenis ?: 'Gejala umum' }}</small>
                            </span>
                        </div>
                        <select name="gejala[{{ $g->id_gejala }}]" class="form-select" aria-label="Tingkat keyakinan: {{ $g->nama_gejala }}" required>
                            @foreach($skala as $s)
                                <option value="{{ $s->nilai_cf }}" @selected((string) $nilaiTerpilih === (string) $s->nilai_cf)>{{ $s->label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i>
                Belum ada gejala yang tersedia untuk konsultasi.
            </div>
        @endif

        <div class="workflow-form-footer mt-4 pt-3 border-top">
            <span class="small text-muted"><i class="bi bi-shield-check text-primary me-1"></i> Pastikan jawaban sesuai kondisi pasien.</span>
            <button type="submit" class="btn btn-primary text-white" @disabled($gejala->isEmpty())>
                Proses Diagnosis <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .symptom-list { display: grid; gap: 10px; }
    .symptom-item {
        display: grid; grid-template-columns: minmax(0, 1fr) minmax(210px, .55fr);
        align-items: center; gap: 18px; padding: 13px 15px;
        border: 1px solid #e3edf1; border-radius: 13px; background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .symptom-item:focus-within { border-color: #9acacb; box-shadow: 0 0 0 3px rgba(8,127,140,.07); }
    .symptom-label { display: flex; align-items: center; gap: 11px; min-width: 0; }
    .symptom-icon { display: grid; width: 38px; height: 38px; flex: 0 0 38px; place-items: center; border-radius: 11px; color: #075e68; background: #e7f6f5; }
    .symptom-label strong, .symptom-label small { display: block; }
    .symptom-label strong { font-size: .88rem; }
    .symptom-label small { margin-top: 4px; color: #718397; font-size: .73rem; }
    @media (max-width: 700px) {
        .symptom-item { grid-template-columns: 1fr; gap: 10px; }
    }
</style>
@endpush
