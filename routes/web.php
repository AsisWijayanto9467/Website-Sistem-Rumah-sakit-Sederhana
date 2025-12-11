<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DetailKunjunganController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/Beranda', [BerandaController::class, 'index'])->name('admin.beranda');

    // Halaman pasien
    Route::get('/admin/pasien',[PasienController::class, 'index'])->name('pasien.index');
    Route::get('/admin/pasien/create',[PasienController::class, 'create'])->name('pasien.create');
    Route::post('/admin/pasien',[PasienController::class, 'store'])->name('pasien.store');
    Route::get('/admin/pasien/{id}',[PasienController::class, 'show'])->name('pasien.show');
    Route::get('/admin/pasien/{id}',[PasienController::class, 'edit'])->name('pasien.edit');
    Route::put('/admin/pasien/{id}',[PasienController::class, 'update'])->name('pasien.update');
    Route::delete('/admin/pasien/{id}',[PasienController::class, 'destroy'])->name('pasien.destroy');

    // Halaman pasien
    Route::get('/admin/dokter',[DokterController::class, 'index'])->name('dokter.index');
    Route::get('/admin/dokter/create',[DokterController::class, 'create'])->name('dokter.create');
    Route::post('/admin/dokter',[DokterController::class, 'store'])->name('dokter.store');
    Route::get('/admin/dokter/{id}',[DokterController::class, 'show'])->name('dokter.show');
    Route::get('/admin/dokter/{id}',[DokterController::class, 'edit'])->name('dokter.edit');
    Route::put('/admin/dokter/{id}',[DokterController::class, 'update'])->name('dokter.update');
    Route::delete('/admin/dokter/{id}',[DokterController::class, 'destroy'])->name('dokter.destroy');

    // Halaman pasien
    Route::get('/admin/services',[ServiceController::class, 'index'])->name('services.index');
    Route::get('/admin/services',[ServiceController::class, 'create'])->name('services.create');
    Route::post('/admin/services',[ServiceController::class, 'store'])->name('services.store');
    Route::get('/admin/service/{id}',[ServiceController::class, 'edit'])->name('service.edit');
    Route::put('/admin/service/{id}',[ServiceController::class, 'update'])->name('service.update');
    Route::delete('/admin/service/{id}',[ServiceController::class, 'destroy'])->name('service.destroy');

    // Halaman Kunjungan
    Route::get('/admin/kunjungan/pending',[KunjunganController::class, 'pending'])->name('kunjungan.pending');
    Route::get('/admin/kunjungan/completed',[KunjunganController::class, 'completed'])->name('kunjungan.completed');
    Route::get('/admin/kunjungan',[KunjunganController::class, 'create'])->name('kunjungan.create');
    Route::post('/admin/kunjungan',[KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::put('/admin/kunjungan/{id}',[KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::put('/admin/kunjungan/{id}',[KunjunganController::class, 'edit'])->name('kunjungan.edit');
    Route::delete('/admin/kunjungan/{id}',[KunjunganController::class, 'destroy'])->name('kunjungan.destroy');
    
    // Halaman Kunjungan
    Route::get('/admin/detail/kunjungan',[DetailKunjunganController::class, 'index'])->name('detailKunjungan.index');
    Route::get('/admin/detail/kunjungan',[DetailKunjunganController::class, 'create'])->name('detailKunjungan.create');
    Route::post('/admin/detail/kunjungan',[DetailKunjunganController::class, 'store'])->name('detailKunjungan.store');
    Route::put('/admin/detail/kunjungan/{id}',[DetailKunjunganController::class, 'update'])->name('detailKunjungan.update');
    Route::put('/admin/detail/kunjungan/{id}',[DetailKunjunganController::class, 'edit'])->name('detailKunjungan.edit');
    Route::delete('/admin/detail/kunjungan/{id}',[DetailKunjunganController::class, 'destroy'])->name('detailKunjungan.destroy');
});