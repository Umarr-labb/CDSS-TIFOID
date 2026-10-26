@extends('layouts.app')

@section('title', 'Dashboard Perawat')
@section('page-title', 'Dashboard Perawat')
@section('page-subtitle', 'Ringkasan akses dan layanan konsultasi pasien')

@section('content')
<div class="panel p-0 overflow-hidden">
    <div class="p-4 p-md-5 text-white" style="background: linear-gradient(120deg, #087f8c, #075e68);">
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-4 bg-white bg-opacity-25" style="width: 54px; height: 54px;">
                <i class="bi bi-person-heart fs-3"></i>
            </div>
            <div>
                <div class="small text-white-50">Portal Perawat</div>
                <h2 class="h3 fw-bold mb-0">Selamat datang, {{ Auth::user()->name }}</h2>
            </div>
        </div>
        <p class="mb-0 text-white-50">Kelola data konsultasi pasien dan dukung alur pelayanan klinis.</p>
    </div>
    <div class="p-4">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('konsultasi.createPasien') }}" class="quick-action h-100">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Mulai Konsultasi Baru</span>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('konsultasi.riwayat') }}" class="quick-action h-100">
                    <i class="bi bi-clock-history"></i>
                    <span>Lihat Riwayat Diagnosis</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
