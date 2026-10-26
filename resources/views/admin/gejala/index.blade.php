@extends('layouts.app')

@section('title', 'Data Gejala')
@section('page-title', 'Data Gejala')
@section('page-subtitle', 'Kelola daftar gejala diagnosis tifoid')

@section('content')
<div class="panel">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel-header">
        <h5><i class="bi bi-list-check text-primary"></i> Daftar Gejala</h5>
        <a href="{{ route('gejala.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah Gejala</a>
    </div>

    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Gejala</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gejala as $item)
                    <tr>
                        <td><code>{{ $item->kode_gejala }}</code></td>
                        <td>{{ $item->nama_gejala }}</td>
                        <td>{{ $item->jenis ?: '-' }}</td>
                        <td>{{ $item->keterangan ?: '-' }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('gejala.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('gejala.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus gejala ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data gejala.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $gejala->links() }}</div>
</div>
@endsection
