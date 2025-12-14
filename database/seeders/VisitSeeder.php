<?php

namespace Database\Seeders;

use App\Models\Visits;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Visits::insert([
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'poliklinik_id' => 1,
                'tanggal_kunjungan' => Carbon::now()->subDays(2)->toDateString(),
                'waktu_kunjungan' => '09:00:00',
                'Alasan' => 'Pemeriksaan kesehatan umum',
                'status' => 'aktif',
                'aksi' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => 2,
                'doctor_id' => 2,
                'poliklinik_id' => 2,
                'tanggal_kunjungan' => Carbon::now()->subDay()->toDateString(),
                'waktu_kunjungan' => '10:30:00',
                'Alasan' => 'Keluhan sakit kepala',
                'status' => 'aktif',
                'aksi' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'patient_id' => 3,
                'doctor_id' => 1,
                'poliklinik_id' => 3,
                'tanggal_kunjungan' => Carbon::now()->toDateString(),
                'waktu_kunjungan' => '13:15:00',
                'Alasan' => 'Kontrol pasca pengobatan',
                'status' => 'tidak aktif',
                'aksi' => 'not approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
