@extends('layouts.app')

@section('title', 'Dashboard Perawat')
@section('page-title', 'Dashboard Perawat')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-person-plus-fill', 'color' => '',
            'value' => $pasien_baru, 'label' => 'Pasien Baru Hari Ini'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-hourglass-split', 'color' => 'orange',
            'value' => $antrian, 'label' => 'Antrian Proses'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-people-fill', 'color' => 'green',
            'value' => $total_pasien, 'label' => 'Total Pasien'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-clipboard2-check-fill', 'color' => 'red',
            'value' => $konsultasi_selesai, 'label' => 'Selesai'
        ])
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-lightning-charge-fill text-warning"></i> Aksi Cepat</h5>
    </div>
    <div class="row g-3">
        <div class="col-6 col-md-6">
            <a href="{{ route('konsultasi.createPasien') }}" class="quick-action">
                <i class="bi bi-person-plus-fill"></i>
                <span>Daftar Pasien Baru</span>
            </a>
        </div>
        <div class="col-6 col-md-6">
            <a href="{{ route('konsultasi.riwayat') }}" class="quick-action">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Pasien</span>
            </a>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-clock-history text-primary"></i> Antrian Pasien Terbaru</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>Umur</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsultasiTerbaru as $k)
                    <tr>
                        <td><code>{{ $k->pasien->no_rm ?? '-' }}</code></td>
                        <td><strong>{{ $k->pasien->nama_pasien ?? '-' }}</strong></td>
                        <td>{{ $k->pasien->umur ?? '-' }} th</td>
                        <td>
                            <span class="badge-role badge-{{ $k->status === 'selesai' ? 'kuat' : 'sedang' }}">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada antrian</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
