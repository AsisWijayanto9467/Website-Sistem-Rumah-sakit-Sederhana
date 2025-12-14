<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Admin Sistem',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nomor_telpon' => '089965432120',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dr. Gunawan',
                'email' => 'dokter@example.com',
                'password' => Hash::make('dokter123'),
                'role' => 'doctor',
                'nomor_telpon' => '089987654432',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Ahmad Pasien',
                'email' => 'pasien@example.com',
                'password' => Hash::make('pasien123'),
                'role' => 'patient',
                'nomor_telpon' => '086754321120',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
