@extends('layouts.app')

@section('title', 'Riwayat Konsultasi')
@section('page-title', 'Riwayat Konsultasi')
@section('page-subtitle', 'Data pasien dan hasil diagnosis yang telah disimpan')

@section('content')
<div class="panel">
    <div class="panel-header flex-wrap gap-3">
        <div>
            <h5><i class="bi bi-clock-history text-primary me-1"></i> Daftar Konsultasi</h5>
            <span class="text-muted small">Tinjau status konsultasi dan hasil diagnosis pasien.</span>
        </div>
        <a href="{{ route('konsultasi.createPasien') }}" class="btn btn-primary btn-sm text-white">
            <i class="bi bi-plus-circle me-1"></i> Konsultasi Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Nomor Rekam Medis</th>
                    <th>Nama Pasien</th>
                    <th>Umur</th>
                    <th>Status</th>
                    <th>Diagnosis Utama</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsultasi as $item)
                    @php($diagnosis = $item->hasil->sortByDesc('cf_gabungan')->first())
                    <tr>
                        <td><code>{{ $item->pasien->no_rm ?? '-' }}</code></td>
                        <td><strong>{{ $item->pasien->nama_pasien ?? '-' }}</strong></td>
                        <td>{{ $item->pasien->umur ? $item->pasien->umur . ' tahun' : '-' }}</td>
                        <td>
                            @if($item->status === 'selesai')
                                <span class="badge-role badge-kuat"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                            @else
                                <span class="badge-role badge-sedang">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($diagnosis)
                                <strong>{{ $diagnosis->penyakit->nama_penyakit ?? '-' }}</strong>
                                <small class="d-block text-muted">{{ number_format($diagnosis->persentase, 1) }}% kecocokan</small>
                            @else
                                <span class="text-muted">Belum ada hasil</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($diagnosis)
                                <a href="{{ route('konsultasi.hasil', $item->id_konsultasi) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Lihat hasil
                                </a>
                            @else
                                <span class="text-muted small">Belum tersedia</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-clipboard2-pulse d-block fs-2 text-secondary mb-2"></i>
                            <span class="text-muted">Belum ada data konsultasi tersimpan.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
