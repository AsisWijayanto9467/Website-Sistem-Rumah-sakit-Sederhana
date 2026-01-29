<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dokters = [
            [
                'nama' => 'Dr. Gunawan',
                'email' => 'dokter@example.com',
                'poliklinik_id' => 1,
                'tarif_konsultasi' => 50000,
                'lama_pengalaman' => 8,
                'pendidikan' => 'S.Ked, Sp.PD',
                'nomor_telpon' => '089987654432',
            ],
            [
                'nama' => 'Dr. Andi Saputra',
                'email' => 'andi@clinic.com',
                'poliklinik_id' => 1,
                'tarif_konsultasi' => 50000,
                'lama_pengalaman' => 5,
                'pendidikan' => 'S.Ked, Sp.PD',
                'nomor_telpon' => '081234567890',
            ],
            [
                'nama' => 'Dr. Siti Rahma',
                'email' => 'siti@clinic.com',
                'poliklinik_id' => 2,
                'tarif_konsultasi' => 65000,
                'lama_pengalaman' => 7,
                'pendidikan' => 'S.Ked, Sp.M',
                'nomor_telpon' => '081298765321',
            ],
            [
                'nama' => 'Dr. Bambang Pratama',
                'email' => 'bambang@clinic.com',
                'poliklinik_id' => 3,
                'tarif_konsultasi' => 80000,
                'lama_pengalaman' => 10,
                'pendidikan' => 'S.Ked, Sp.N',
                'nomor_telpon' => '082233445566',
            ],
        ];

        foreach ($dokters as $data) {
            $user = User::create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make('password123'), 
                'role' => 'doctor',
            ]);

            Doctor::create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'poliklinik_id' => $data['poliklinik_id'],
                'tarif_konsultasi' => $data['tarif_konsultasi'],
                'lama_pengalaman' => $data['lama_pengalaman'],
                'pendidikan' => $data['pendidikan'],
                'nomor_telpon' => $data['nomor_telpon'],
                'status' => 'aktif',
            ]);
        }
    }
}
