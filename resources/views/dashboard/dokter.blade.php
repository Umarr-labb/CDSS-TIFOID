@extends('layouts.app')

@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard Dokter')
@section('page-subtitle', 'Selamat datang, dr. ' . auth()->user()->name)

@section('content')

{{-- Statistik --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-calendar-day', 'color' => '',
            'value' => $konsultasi_hari_ini, 'label' => 'Konsultasi Hari Ini'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-calendar-week', 'color' => 'green',
            'value' => $konsultasi_minggu, 'label' => 'Konsultasi Minggu Ini'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-people-fill', 'color' => 'orange',
            'value' => $total_pasien, 'label' => 'Total Pasien'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-clipboard2-check-fill', 'color' => 'red',
            'value' => $konsultasi_selesai, 'label' => 'Total Diagnosis'
        ])
    </div>
</div>

{{-- Aksi Cepat Dokter --}}
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-lightning-charge-fill text-warning"></i> Aksi Cepat</h5>
    </div>
    <div class="row g-3">
        <div class="col-6 col-md-6">
            <a href="{{ route('konsultasi.createPasien') }}" class="quick-action">
                <i class="bi bi-stethoscope"></i>
                <span>Konsultasi Baru</span>
            </a>
        </div>
        <div class="col-6 col-md-6">
            <a href="{{ route('konsultasi.riwayat') }}" class="quick-action">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat Diagnosis</span>
            </a>
        </div>
    </div>
</div>

{{-- Grafik --}}
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-graph-up-arrow text-primary"></i> Tren Konsultasi 7 Hari Terakhir</h5>
    </div>
    <canvas id="chartKonsultasi" height="80"></canvas>
</div>

{{-- Tabel Konsultasi Terbaru --}}
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-clipboard2-pulse text-primary"></i> Pasien Terbaru</h5>
        <a href="{{ route('konsultasi.riwayat') }}" class="badge-soft text-decoration-none">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>Pasien</th>
                    <th>Umur</th>
                    <th>Diagnosis</th>
                    <th>CF</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsultasiTerbaru as $k)
                    @php $hasil = $k->hasil->sortByDesc('cf_gabungan')->first(); @endphp
                    <tr>
                        <td><code>{{ $k->pasien->no_rm ?? '-' }}</code></td>
                        <td><strong>{{ $k->pasien->nama_pasien ?? '-' }}</strong></td>
                        <td>{{ $k->pasien->umur ?? '-' }} th</td>
                        <td>{{ $hasil->penyakit->nama_penyakit ?? '-' }}</td>
                        <td>{{ $hasil ? number_format($hasil->persentase, 1).'%' : '-' }}</td>
                        <td>
                            <a href="{{ route('konsultasi.hasil', $k->id_konsultasi) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    new Chart(document.getElementById('chartKonsultasi'), {
        type: 'bar',
        data: {
            labels: @json(array_column($grafik7Hari, 'tanggal')),
            datasets: [{
                label: 'Konsultasi',
                data: @json(array_column($grafik7Hari, 'jumlah')),
                backgroundColor: 'rgba(8,127,140,.72)',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
