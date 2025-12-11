<?php

namespace Database\Seeders;

use App\Models\Patients;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => 'patient',
            'password' => Hash::make('password123')
        ]);

        // === 2. Buat Pasien (Relasi ke user) ===
        Patients::create([
            'user_id'           => $user->id,
            'nama'              => 'Budi Santoso',
            'nomor_telpon'      => '08123456789',
            'gender'            => 'laki-laki',
            'tipe_darah'        => 'O',
            'tanggal_lahir'     => '2000-05-12',
            'alamat'            => 'Jl. Merdeka No. 10',
            'tanggal_registrasi'=> Carbon::now()->format('Y-m-d'),
            'waktu_daftar'      => Carbon::now()->format('Y-m-d H:i:s'),
            'kota'              => 'Jakarta',
            'nomor_identitas'   => '1234567891234567',
        ]);

        // >>> Jika mau tambah sample lain, copy block bawah ini:

        $user2 = User::create([
            'nama' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'role' => 'patient',
            'password' => Hash::make('password123')
        ]);

        Patients::create([
            'user_id'           => $user2->id,
            'nama'              => 'Siti Aminah',
            'nomor_telpon'      => '08987654321',
            'gender'            => 'perempuan',
            'tipe_darah'        => 'A',
            'tanggal_lahir'     => '2001-03-24',
            'alamat'            => 'Jl. Anggrek No. 5',
            'tanggal_registrasi'=> Carbon::now()->format('Y-m-d'),
            'waktu_daftar'      => Carbon::now()->format('Y-m-d H:i:s'),
            'kota'              => 'Bandung',
            'nomor_identitas'   => '9876543219876543',
        ]);
    }
}
