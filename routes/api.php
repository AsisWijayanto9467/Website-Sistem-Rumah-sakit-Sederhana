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
        Route::get('/admin/beranda', [BerandaController::class, 'index']);

        Route::get('/profile', [PengaturanController::class, 'index']);
        Route::put('/profile', [PengaturanController::class, 'update']);
        Route::put('/password/update', [PengaturanController::class, 'ubah_password']);

        Route::prefix("admin")->group(function() {
             // Detail Kunjungan
            Route::get('/detail/create/kunjungan/{visitId}',[DetailKunjunganController::class, 'index']);
            Route::get('/detail/index/kunjungan/{visitId}',[DetailKunjunganController::class, 'create']);
            Route::post('/detail/kunjungan/{visitId}',[DetailKunjunganController::class, 'store']);
            Route::put('/detail/kunjungan/{id}',[DetailKunjunganController::class, 'update']);
            Route::get('/detail/kunjungan/{id}',[DetailKunjunganController::class, 'edit']);
            Route::delete('/detail/kunjungan/{id}',[DetailKunjunganController::class, 'destroy']);

            Route::get('/dokter',[DokterController::class, 'index']);
            Route::get('/dokter/create',[DokterController::class, 'create']);
            Route::post('/dokter',[DokterController::class, 'store']);
            Route::get('/dokter/{id}',[DokterController::class, 'show']);
            Route::get('/dokter/poliklinik/{id}',[DokterController::class, 'edit']);
            Route::put('/dokter/{id}',[DokterController::class, 'update']);
            Route::delete('/dokter/{id}',[DokterController::class, 'destroy']);

             Route::get('/check-report-status/{visitId}', [KunjunganController::class, 'checkReportStatus']);
            Route::get('/showLaporan/{visitId}', [KunjunganController::class, 'viewAdminLaporan']);
            Route::get('/download-laporan/{visitId}', [KunjunganController::class, 'downloadAdminLaporan']);

            Route::get('/kunjungan/pending',[KunjunganController::class, 'pending']);
            Route::get('/kunjungan/not-approved',[KunjunganController::class, 'notApproved']);
            Route::get('/kunjungan',[KunjunganController::class, 'create']);
            Route::post('/kunjungan',[KunjunganController::class, 'store']);
            Route::put('/kunjungan/approved/{id}',[KunjunganController::class, 'approve']);
            Route::put('/kunjungan/reject/{id}',[KunjunganController::class, 'reject']);
            Route::put('/kunjungan/cencle-approved/{id}',[KunjunganController::class, 'cancelApproval']);
            Route::put('/kunjungan/approve-kembali/{id}', [KunjunganController::class, 'approveKembali']);
            Route::put('/kunjungan/{id}',[KunjunganController::class, 'update']);
            Route::get('/kunjungan/{id}',[KunjunganController::class, 'edit']);
            Route::delete('/kunjungan/{id}',[KunjunganController::class, 'destroy']);

            // medication
            Route::get('/Medicine',[MedicationController::class, 'index']);
            Route::post('/Medicine',[MedicationController::class, 'store']);
            Route::get('/Medicine/{id}',[MedicationController::class, 'show']);
            Route::put('/Medicine/{id}',[MedicationController::class, 'update']);
            Route::delete('/Medicine/{id}',[MedicationController::class, 'destroy']);

            // Pasien
            Route::get('/pasien',[PasienController::class, 'index']);
            Route::post('/pasien',[PasienController::class, 'store']);
            Route::get('/pasien/{id}',[PasienController::class, 'show']);
            Route::put('/pasien/{id}',[PasienController::class, 'update']);
            Route::delete('/pasien/{id}',[PasienController::class, 'destroy']);

        

            // Poliklinik
            Route::get('/poliklinik',[PoliklinikController::class, 'index']);
            Route::post('/poliklinik',[PoliklinikController::class, 'store']);
            Route::get('/poliklinik/{id}',[PoliklinikController::class, 'show']);
            Route::put('/poliklinik/{id}',[PoliklinikController::class, 'update']);
            Route::delete('/poliklinik/{id}',[PoliklinikController::class, 'destroy']);
            
            // Services
            Route::get('/services',[ServiceController::class, 'index']);
            Route::post('/services',[ServiceController::class, 'store']);
            Route::get('/service/{id}',[ServiceController::class, 'show']);
            Route::put('/service/{id}',[ServiceController::class, 'update']);
            Route::delete('/service/{id}',[ServiceController::class, 'destroy']);
        });
       

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
    });
});