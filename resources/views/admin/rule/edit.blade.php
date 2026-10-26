@extends('layouts.app')

@section('title', 'Edit Rule')
@section('page-title', 'Edit Rule')
@section('page-subtitle', 'Perbarui aturan diagnosis')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-pencil-square text-primary"></i> Edit Rule</h5>
    </div>

    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('rule.update', $rule) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.rule.form', ['selectedGejala' => $selectedGejala])
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Perbarui</button>
            <a href="{{ route('rule.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
