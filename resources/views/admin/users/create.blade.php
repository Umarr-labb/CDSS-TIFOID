@extends('layouts.app')

@section('title', 'Tambah Akun')
@section('page-title', 'Tambah Akun Login')
@section('page-subtitle', 'Buat akun baru untuk dokter atau perawat')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h5><i class="bi bi-person-plus-fill text-primary"></i> Form Akun Baru</h5>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email Login</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="">Pilih role</option>
                    <option value="dokter" @selected(old('role') === 'dokter')>Dokter</option>
                    <option value="perawat" @selected(old('role') === 'perawat')>Perawat</option>
                </select>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control" minlength="8" required>
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" minlength="8" required>
            </div>
        </div>
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Simpan Akun</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
