<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BerandaController;
use App\Http\Controllers\API\DetailKunjunganController;
use App\Http\Controllers\API\DokterController;
use App\Http\Controllers\API\JadwalController;
use App\Http\Controllers\API\KunjunganController;
use App\Http\Controllers\API\MedicationController;
use App\Http\Controllers\API\PasienController;
use App\Http\Controllers\API\PengaturanController;
use App\Http\Controllers\API\PoliklinikController;
use App\Http\Controllers\API\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix("v1")->group(function() {
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware("auth:sanctum")->group(function() {
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get('/admin/Beranda', [BerandaController::class, 'index']);

        // Detail Kunjungan
        Route::get('/admin/detail/kunjungan',[DetailKunjunganController::class, 'index']);
        Route::get('/admin/detail/kunjungan',[DetailKunjunganController::class, 'create']);
        Route::post('/admin/detail/kunjungan',[DetailKunjunganController::class, 'store']);
        Route::put('/admin/detail/kunjungan/{id}',[DetailKunjunganController::class, 'update']);
        Route::put('/admin/detail/kunjungan/{id}',[DetailKunjunganController::class, 'edit']);
        Route::delete('/admin/detail/kunjungan/{id}',[DetailKunjunganController::class, 'destroy']);

        Route::get('/admin/dokter',[DokterController::class, 'index']);
        Route::get('/admin/dokter/create',[DokterController::class, 'create']);
        Route::post('/admin/dokter',[DokterController::class, 'store']);
        Route::get('/admin/dokter/{id}',[DokterController::class, 'show']);
        Route::get('/admin/dokter/{id}',[DokterController::class, 'edit']);
        Route::put('/admin/dokter/{id}',[DokterController::class, 'update']);
        Route::delete('/admin/dokter/{id}',[DokterController::class, 'destroy']);

        // tampilkan pasien berdasarkan id dokter
        Route::get('/dokter/showPasien', [DokterController::class, 'showPasien']);

        // Jadwal
        Route::get('/dokter/jadwals',[JadwalController::class, 'index']);
        Route::post('/dokter/jadwals',[JadwalController::class, 'store']);
        Route::get('/dokter/jadwal/{id}',[JadwalController::class, 'show']);
        Route::delete('/dokter/jadwal/{id}',[JadwalController::class, 'destroy']);

        // Kunjungan untuk dokter
        Route::get('/dokter/kunjungan', [KunjunganController::class, 'kunjunganDokter']);
        Route::get('/dokter/buatLaporan/{visitId}', [KunjunganController::class, 'buatLaporan']);
        Route::post('/dokter/storeLaporan/{visitId}', [KunjunganController::class, 'storeLaporan']);
        Route::get('/dokter/editLaporan/{visitId}', [KunjunganController::class, 'editLaporan']);
        Route::put('/dokter/updateLaporan/{visitId}', [KunjunganController::class, 'updateLaporan']);
        Route::get('/dokter/showLaporan/{visitId}', [KunjunganController::class, 'viewLaporan']);
        Route::get('/dokter/downloadLaporan/{visitId}', [KunjunganController::class, 'downloadLaporan']);

        Route::get('/check-report-status/{visitId}', [KunjunganController::class, 'checkReportStatus']);
        Route::get('/admin/showLaporan/{visitId}', [KunjunganController::class, 'viewAdminLaporan']);
        Route::get('/admin/download-laporan/{visitId}', [KunjunganController::class, 'downloadAdminLaporan']);

        Route::get('/admin/kunjungan/pending',[KunjunganController::class, 'pending']);
        Route::get('/admin/kunjungan/not-approved',[KunjunganController::class, 'notApproved']);
        Route::get('/admin/kunjungan',[KunjunganController::class, 'create']);
        Route::post('/admin/kunjungan',[KunjunganController::class, 'store']);
        Route::put('/admin/kunjungan/approved/{id}',[KunjunganController::class, 'approve']);
        Route::put('/admin/kunjungan/reject/{id}',[KunjunganController::class, 'reject']);
        Route::put('/admin/kunjungan/cencle-approved/{id}',[KunjunganController::class, 'cancelApproval']);
        Route::put('/admin/kunjungan/approve-kembali/{id}', [KunjunganController::class, 'approveKembali']);
        Route::put('/admin/kunjungan/{id}',[KunjunganController::class, 'update']);
        Route::get('/admin/kunjungan/{id}',[KunjunganController::class, 'edit']);
        Route::delete('/admin/kunjungan/{id}',[KunjunganController::class, 'destroy']);

        // medication
        Route::get('/admin/Medicine',[MedicationController::class, 'index']);
        Route::post('/admin/Medicine',[MedicationController::class, 'store']);
        Route::get('/admin/Medicine/{id}',[MedicationController::class, 'show']);
        Route::put('/admin/Medicine/{id}',[MedicationController::class, 'update']);
        Route::delete('/admin/Medicine/{id}',[MedicationController::class, 'destroy']);

        // Pasien
        Route::get('/admin/pasien',[PasienController::class, 'index']);
        Route::post('/admin/pasien',[PasienController::class, 'store']);
        Route::get('/admin/pasien/{id}',[PasienController::class, 'show']);
        Route::put('/admin/pasien/{id}',[PasienController::class, 'update']);
        Route::delete('/admin/pasien/{id}',[PasienController::class, 'destroy']);

        // Pengaturan
        Route::get('/profile', [PengaturanController::class, 'index']);
        Route::put('/profile', [PengaturanController::class, 'update']);
        Route::put('/password/update', [PengaturanController::class, 'ubah_password']);

        // Poliklinik
        Route::get('/admin/poliklinik',[PoliklinikController::class, 'index']);
        Route::post('/admin/poliklinik',[PoliklinikController::class, 'store']);
        Route::get('/admin/poliklinik/{id}',[PoliklinikController::class, 'show']);
        Route::put('/admin/poliklinik/{id}',[PoliklinikController::class, 'update']);
        Route::delete('/admin/poliklinik/{id}',[PoliklinikController::class, 'destroy']);
        
        // Services
        Route::get('/admin/services',[ServiceController::class, 'index']);
        Route::post('/admin/services',[ServiceController::class, 'store']);
        Route::get('/admin/service/{id}',[ServiceController::class, 'show']);
        Route::put('/admin/service/{id}',[ServiceController::class, 'update']);
        Route::delete('/admin/service/{id}',[ServiceController::class, 'destroy']);
    });
});