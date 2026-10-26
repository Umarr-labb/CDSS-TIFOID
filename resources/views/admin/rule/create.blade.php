@extends('layouts.app')

@section('title', 'Tambah Rule')
@section('page-title', 'Tambah Rule')
@section('page-subtitle', 'Tambahkan aturan diagnosis baru')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-plus-square-fill text-primary"></i> Form Rule</h5>
    </div>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('rule.store') }}" method="POST">
        @csrf
        @include('admin.rule.form')
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan</button>
            <a href="{{ route('rule.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
