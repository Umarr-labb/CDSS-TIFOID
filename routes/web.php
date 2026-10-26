<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonsultasiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes are handled by the application's custom controller.
Route::get('/login', [AuthController::class, 'showLoginSelector'])->name('login');
Route::get('/login/admin', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('admin.login.post');

Route::get('/login/dokter', [AuthController::class, 'showDokterLoginForm'])->name('dokter.login');
Route::post('/login/dokter', [AuthController::class, 'loginDokter'])->name('dokter.login.post');

Route::get('/login/perawat', [AuthController::class, 'showPerawatLoginForm'])->name('perawat.login');
Route::post('/login/perawat', [AuthController::class, 'loginPerawat'])->name('perawat.login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Dashboard (semua role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menu khusus Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        Route::resource('penyakit', \App\Http\Controllers\PenyakitController::class);
        Route::resource('gejala', \App\Http\Controllers\GejalaController::class);
        Route::resource('rule', \App\Http\Controllers\RuleController::class);
    });

    // Menu Dokter & Perawat (diagnosis)
    Route::middleware(['role:admin,dokter,perawat'])->prefix('konsultasi')->group(function () {
        Route::get('/', [KonsultasiController::class, 'index'])->name('konsultasi.index');
        Route::get('/pasien', [KonsultasiController::class, 'createPasien'])->name('konsultasi.createPasien');
        Route::post('/pasien', [KonsultasiController::class, 'storePasien'])->name('konsultasi.storePasien');
        Route::get('/gejala/{id_pasien}', [KonsultasiController::class, 'formGejala'])->name('konsultasi.gejala');
        Route::post('/proses/{id_pasien}', [KonsultasiController::class, 'proses'])->name('konsultasi.proses');
        Route::get('/hasil/{id_konsultasi}', [KonsultasiController::class, 'hasil'])->name('konsultasi.hasil');
        Route::get('/hasil/{id_konsultasi}/word', [KonsultasiController::class, 'downloadWord'])
            ->name('konsultasi.hasil.word');
        Route::get('/riwayat', [KonsultasiController::class, 'riwayat'])->name('konsultasi.riwayat');
        Route::delete('/riwayat/{id_konsultasi}', [KonsultasiController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('konsultasi.destroy');
    });
});