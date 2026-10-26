@extends('layouts.app')

@section('title', 'Hasil Diagnosis')
@section('page-title', 'Hasil Diagnosis')
@section('page-subtitle', 'Ringkasan analisis konsultasi dan diagnosis diferensial')

@section('content')
@php
    $hasilDiagnosis = $konsultasi->hasil->sortByDesc('cf_gabungan')->values();
    $diagnosisUtama = $hasilDiagnosis->first();
@endphp

<div class="result-patient panel py-3 px-3 px-md-4">
    <div class="d-flex align-items-center gap-3">
        <span class="result-patient-icon"><i class="bi bi-person-fill"></i></span>
        <div>
            <small class="text-muted d-block">Nama pasien</small>
            <strong>{{ $konsultasi->pasien->nama_pasien ?? '-' }}</strong>
        </div>
    </div>
    <div class="result-record">
        <small class="text-muted d-block">Nomor rekam medis</small>
        <strong>{{ $konsultasi->pasien->no_rm ?? '-' }}</strong>
    </div>
</div>

@if($diagnosisUtama)
    <section class="diagnosis-highlight mb-4">
        <div class="diagnosis-copy">
            <span class="result-pill"><i class="bi bi-check-circle-fill"></i> Diagnosis dengan kecocokan tertinggi</span>
            <h2>{{ $diagnosisUtama->penyakit->nama_penyakit ?? 'Penyakit tidak tersedia' }}</h2>
            <p>{{ $diagnosisUtama->penyakit->deskripsi ?? 'Hasil diagnosis berdasarkan gejala yang dipilih.' }}</p>
        </div>
        <div class="diagnosis-score">
            <strong>{{ number_format($diagnosisUtama->persentase, 1) }}%</strong>
            <span>Tingkat kecocokan</span>
        </div>
    </section>
@else
    <div class="panel text-center text-muted py-4">
        <i class="bi bi-info-circle me-1"></i> Belum ada hasil diagnosis untuk konsultasi ini.
    </div>
@endif

<section class="panel">
    <div class="panel-header">
        <div>
            <h5><i class="bi bi-bar-chart-line-fill text-primary me-1"></i> Diagnosis Diferensial</h5>
            <small class="text-muted">Perbandingan nilai kepastian untuk setiap kemungkinan diagnosis.</small>
        </div>
    </div>

    @if($hasilDiagnosis->isNotEmpty())
        <div class="differential-list">
            @foreach($hasilDiagnosis as $hasil)
                @php($persentase = min(100, max(0, (float) $hasil->persentase)))
                <div class="differential-item">
                    <div class="differential-name">
                        <span class="rank-badge">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>
                            <strong>{{ $hasil->penyakit->nama_penyakit ?? 'Penyakit tidak tersedia' }}</strong>
                            <small>{{ $hasil->kategori ?: 'Hasil perhitungan CF' }} · CF {{ number_format($hasil->cf_gabungan, 4) }}</small>
                        </span>
                    </div>
                    <div class="progress" role="progressbar" aria-label="Tingkat kecocokan {{ $hasil->penyakit->nama_penyakit ?? 'penyakit' }}" aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $persentase }}%"></div>
                    </div>
                    <strong class="percentage">{{ number_format($hasil->persentase, 1) }}%</strong>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted text-center py-3 mb-0">Tidak ada diagnosis diferensial yang dapat ditampilkan.</p>
    @endif
</section>

<section class="panel">
    <div class="panel-header">
        <div>
            <h5><i class="bi bi-clipboard2-check-fill text-primary me-1"></i> Rekomendasi Klinis</h5>
            <small class="text-muted">Tindak lanjut yang tercatat untuk diagnosis utama.</small>
        </div>
    </div>
    @if($rekomendasi->isNotEmpty())
        <ul class="recommendation-list">
            @foreach($rekomendasi as $item)
                <li><i class="bi bi-check2-circle"></i><span><strong>{{ ucfirst($item->kategori ?: 'Umum') }}:</strong> {{ $item->isi_rekomendasi }}</span></li>
            @endforeach
        </ul>
    @else
        <p class="text-muted mb-0">Belum ada rekomendasi khusus yang tercatat untuk diagnosis ini.</p>
    @endif
</section>

<div class="clinical-note mb-4">
    <i class="bi bi-info-circle-fill"></i>
    <span>Hasil ini merupakan pendukung keputusan klinis, bukan pengganti pemeriksaan dan diagnosis dokter. Evaluasi hasil bersama tenaga medis yang berwenang.</span>
</div>

<div class="d-flex justify-content-between flex-wrap gap-2">
    <a href="{{ route('konsultasi.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-clock-history me-1"></i> Riwayat Konsultasi
    </a>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('konsultasi.hasil.word', $konsultasi->id_konsultasi) }}" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-word me-1"></i> Unduh Word
        </a>
        <a href="{{ route('konsultasi.createPasien') }}" class="btn btn-primary text-white">
            <i class="bi bi-plus-circle me-1"></i> Konsultasi Baru
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .result-patient { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .result-patient-icon { display: grid; width: 44px; height: 44px; place-items: center; border-radius: 13px; color: #075e68; background: #e7f6f5; font-size: 1.15rem; }
    .result-record { text-align: right; }
    .diagnosis-highlight {
        display: flex; align-items: center; justify-content: space-between; gap: 24px;
        padding: 28px; border-radius: 18px; color: #fff;
        background: linear-gradient(120deg, #087f8c, #075e68);
        box-shadow: 0 14px 30px rgba(8,127,140,.18);
    }
    .result-pill { display: inline-flex; align-items: center; gap: 7px; padding: 7px 11px; margin-bottom: 12px; border: 1px solid rgba(255,255,255,.24); border-radius: 999px; background: rgba(255,255,255,.12); font-size: .75rem; font-weight: 700; }
    .diagnosis-copy h2 { margin: 0 0 7px; font-size: clamp(1.45rem, 3vw, 2rem); font-weight: 800; letter-spacing: -.04em; }
    .diagnosis-copy p { max-width: 650px; margin: 0; color: rgba(255,255,255,.8); line-height: 1.6; }
    .diagnosis-score { min-width: 140px; text-align: center; }
    .diagnosis-score strong { display: block; font-size: 2.5rem; font-weight: 800; letter-spacing: -.06em; line-height: 1.15; }
    .diagnosis-score span { color: rgba(255,255,255,.78); font-size: .8rem; }
    .differential-list { display: grid; gap: 10px; }
    .differential-item { display: grid; grid-template-columns: minmax(0, 1fr) 160px 65px; align-items: center; gap: 16px; padding: 13px 14px; border: 1px solid #e3edf1; border-radius: 13px; background: #fcfefe; }
    .differential-name { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .rank-badge { display: grid; width: 34px; height: 34px; flex: 0 0 34px; place-items: center; border-radius: 11px; color: #547184; background: #edf3f6; font-size: .8rem; font-weight: 800; }
    .differential-item:first-child .rank-badge { color: #087f8c; background: #e3f5f3; }
    .differential-name strong, .differential-name small { display: block; }
    .differential-name strong { font-size: .88rem; }
    .differential-name small { margin-top: 3px; color: #718397; font-size: .74rem; }
    .differential-item .progress { height: 8px; border-radius: 99px; background: #eaf0f3; }
    .differential-item .progress-bar { border-radius: 99px; background: linear-gradient(90deg, #21a6a1, #087f8c); }
    .percentage { text-align: right; font-size: .88rem; }
    .recommendation-list { display: grid; gap: 10px; padding: 0; margin: 0; list-style: none; }
    .recommendation-list li { display: flex; align-items: flex-start; gap: 10px; padding: 12px 13px; border-radius: 12px; background: #f3f9fa; color: #425c6d; font-size: .88rem; line-height: 1.6; }
    .recommendation-list i { margin-top: 2px; color: #087f8c; }
    .clinical-note { display: flex; align-items: flex-start; gap: 10px; padding: 13px 15px; border: 1px solid #f3e4c2; border-radius: 12px; color: #725d35; background: #fffaf0; font-size: .8rem; line-height: 1.6; }
    .clinical-note i { margin-top: 2px; }
    @media (max-width: 700px) {
        .diagnosis-highlight { align-items: flex-start; flex-direction: column; padding: 22px; }
        .diagnosis-score { text-align: left; }
        .diagnosis-score strong { font-size: 2rem; }
        .differential-item { grid-template-columns: minmax(0, 1fr) auto; gap: 10px; }
        .differential-item .progress { grid-column: 1 / -1; grid-row: 2; }
    }
    @media (max-width: 420px) {
        .result-patient { align-items: flex-start; flex-direction: column; }
        .result-record { text-align: left; }
    }
</style>
@endpush
