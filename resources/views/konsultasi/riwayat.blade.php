@extends('layouts.app')

@section('title', 'Riwayat Diagnosis')
@section('page-title', 'Riwayat Diagnosis')
@section('page-subtitle', 'Daftar seluruh konsultasi yang pernah dilakukan')

@section('content')
<div class="panel">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel-header">
        <h5><i class="bi bi-clock-history text-primary"></i> Riwayat Konsultasi</h5>
        <a href="{{ route('konsultasi.createPasien') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Konsultasi Baru
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. RM</th>
                    <th>Pasien</th>
                    <th>Diagnosis Utama</th>
                    <th>CF</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $i => $r)
                    @php $hasil = $r->hasil->sortByDesc('cf_gabungan')->first(); @endphp
                    <tr>
                        <td>{{ $riwayat->firstItem() + $i }}</td>
                        <td><code>{{ $r->pasien->no_rm ?? '-' }}</code></td>
                        <td><strong>{{ $r->pasien->nama_pasien ?? '-' }}</strong></td>
                        <td>{{ $hasil->penyakit->nama_penyakit ?? '-' }}</td>
                        <td>{{ $hasil ? number_format($hasil->cf_gabungan, 3) : '-' }}</td>
                        <td>
                            @if($hasil)
                                <span class="badge-role badge-{{ $hasil->kategori === 'Kuat' ? 'kuat' : ($hasil->kategori === 'Sedang' ? 'sedang' : 'lemah') }}">
                                    {{ $hasil->kategori }}
                                </span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('konsultasi.hasil', $r->id_konsultasi) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('konsultasi.hasil.word', $r->id_konsultasi) }}" class="btn btn-sm btn-outline-success" title="Unduh hasil dalam Word" aria-label="Unduh hasil {{ $r->pasien->nama_pasien ?? 'pasien' }} dalam Word">
                                <i class="bi bi-file-earmark-word"></i>
                            </a>
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('konsultasi.destroy', $r->id_konsultasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat pasien ini? Data diagnosis terkait juga akan dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus riwayat">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada riwayat konsultasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $riwayat->links() }}
    </div>
</div>
@endsection
