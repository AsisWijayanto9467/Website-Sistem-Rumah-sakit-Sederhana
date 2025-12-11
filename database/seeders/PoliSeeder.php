<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('polikliniks')->insert([
            [
                'nama_poli'  => 'Poli Umum',
                'deskripsi'  => 'Menangani keluhan umum dan pemeriksaan kesehatan dasar.',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_poli'  => 'Poli Mata',
                'deskripsi'  => 'Fokus pada pemeriksaan dan pengobatan gangguan penglihatan.',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_poli'  => 'Poli Saraf',
                'deskripsi'  => 'Melayani pemeriksaan dan penanganan gangguan sistem saraf.',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
