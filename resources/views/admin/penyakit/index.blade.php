@extends('layouts.app')

@section('title', 'Data Penyakit')
@section('page-title', 'Data Penyakit')
@section('page-subtitle', 'Kelola daftar penyakit diagnosis tifoid')

@section('content')
<div class="panel">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel-header">
        <h5><i class="bi bi-virus text-primary"></i> Daftar Penyakit</h5>
        <a href="{{ route('penyakit.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah Penyakit</a>
    </div>

    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Kode ICD-10</th>
                    <th>Nama Penyakit</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyakit as $item)
                    <tr>
                        <td><code>{{ $item->kode_penyakit }}</code></td>
                        <td>{{ $item->nama_penyakit }}</td>
                        <td>{{ Str::limit($item->deskripsi ?: '-', 80) }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('penyakit.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('penyakit.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penyakit ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data penyakit.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $penyakit->links() }}</div>
</div>
@endsection
