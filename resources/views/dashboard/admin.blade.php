@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Ringkasan sistem CDSS Demam Tifoid')

@section('content')

{{-- Statistik Utama --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-people-fill', 'color' => '',
            'value' => $total_user, 'label' => 'Total Pengguna'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-clipboard2-pulse-fill', 'color' => 'green',
            'value' => $total_konsultasi, 'label' => 'Total Konsultasi'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-virus', 'color' => 'orange',
            'value' => $total_penyakit, 'label' => 'Jenis Penyakit'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-diagram-3-fill', 'color' => 'red',
            'value' => $total_rule, 'label' => 'Basis Aturan'
        ])
    </div>
</div>

{{-- Statistik Pendukung --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-person-badge', 'color' => '',
            'value' => $total_dokter, 'label' => 'Dokter Aktif'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-person-heart', 'color' => 'green',
            'value' => $total_perawat, 'label' => 'Perawat Aktif'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-list-check', 'color' => 'orange',
            'value' => $total_gejala, 'label' => 'Total Gejala'
        ])
    </div>
    <div class="col-md-6 col-lg-3">
        @include('dashboard.partials.stat-card', [
            'icon' => 'bi-calendar-check', 'color' => 'red',
            'value' => $konsultasi_hari_ini, 'label' => 'Konsultasi Hari Ini'
        ])
    </div>
</div>

{{-- Quick Actions --}}
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-lightning-charge-fill text-warning"></i> Aksi Cepat</h5>
    </div>
    <div class="row g-3">
        @if(Route::has('users.create'))
            <div class="col-6 col-md-3">
                <a href="{{ route('users.create') }}" class="quick-action">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah User</span>
                </a>
            </div>
        @endif
        <div class="col-6 col-md-3">
            <a href="{{ route('rule.create') }}" class="quick-action">
                <i class="bi bi-plus-square-fill"></i>
                <span>Tambah Rule</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('gejala.create') }}" class="quick-action">
                <i class="bi bi-clipboard-plus-fill"></i>
                <span>Tambah Gejala</span>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('konsultasi.createPasien') }}" class="quick-action">
                <i class="bi bi-stethoscope"></i>
                <span>Mulai Konsultasi</span>
            </a>
        </div>
    </div>
</div>

{{-- Grafik & Distribusi --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h5><i class="bi bi-graph-up-arrow text-primary"></i> Konsultasi 7 Hari Terakhir</h5>
                <span class="badge-soft">Live</span>
            </div>
            <canvas id="chartKonsultasi" height="90"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header">
                <h5><i class="bi bi-pie-chart-fill text-success"></i> Distribusi Diagnosis</h5>
            </div>
            @forelse($distribusiDiagnosis as $d)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong style="font-size:14px;">{{ $d->penyakit->nama_penyakit ?? '-' }}</strong>
                    </div>
                    <span class="badge bg-primary rounded-pill">{{ $d->total }}</span>
                </div>
            @empty
                <p class="text-muted text-center py-4">Belum ada data diagnosis</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Konsultasi Terbaru --}}
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-clock-history text-primary"></i> Konsultasi Terbaru</h5>
        <a href="{{ route('konsultasi.riwayat') }}" class="badge-soft text-decoration-none">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>Pasien</th>
                    <th>Diagnosis</th>
                    <th>CF</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsultasiTerbaru as $k)
                    @php
                        $hasil = $k->hasil->sortByDesc('cf_gabungan')->first();
                    @endphp
                    <tr>
                        <td><code>{{ $k->pasien->no_rm ?? '-' }}</code></td>
                        <td><strong>{{ $k->pasien->nama_pasien ?? '-' }}</strong></td>
                        <td>{{ $hasil->penyakit->nama_penyakit ?? '-' }}</td>
                        <td>{{ $hasil ? number_format($hasil->cf_gabungan, 3) : '-' }}</td>
                        <td>
                            @if($hasil)
                                <span class="badge-role badge-{{ strtolower($hasil->kategori === 'Kuat' ? 'kuat' : ($hasil->kategori === 'Sedang' ? 'sedang' : 'lemah')) }}">
                                    {{ $hasil->kategori }}
                                </span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada konsultasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('chartKonsultasi');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_column($grafik7Hari, 'tanggal')),
            datasets: [{
                label: 'Jumlah Konsultasi',
                data: @json(array_column($grafik7Hari, 'jumlah')),
                borderColor: '#087f8c',
                backgroundColor: 'rgba(8,127,140,.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#087f8c',
                pointRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
