@extends('layouts.app')

@section('title', 'Basis Aturan')
@section('page-title', 'Basis Aturan')
@section('page-subtitle', 'Kelola aturan diagnosis sistem')

@section('content')
<div class="panel">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel-header">
        <h5><i class="bi bi-diagram-3-fill text-primary"></i> Daftar Rule</h5>
        <a href="{{ route('rule.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah Rule</a>
    </div>

    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Penyakit</th>
                    <th>CF Pakar</th>
                    <th>Gejala</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                    <tr>
                        <td><code>{{ $rule->kode_rule }}</code></td>
                        <td>{{ $rule->penyakit->nama_penyakit ?? '-' }}</td>
                        <td>{{ number_format($rule->cf_pakar, 3) }}</td>
                        <td>{{ $rule->details->pluck('gejala.kode_gejala')->filter()->join(', ') ?: '-' }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('rule.edit', $rule) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('rule.destroy', $rule) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rule ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada rule.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $rules->links() }}</div>
</div>
@endsection
