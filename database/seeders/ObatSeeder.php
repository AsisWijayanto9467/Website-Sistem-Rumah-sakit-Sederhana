<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medications')->insert([
            [
                'nama' => 'Paracetamol 500mg',
                'harga' => 15000.00,
                'deskripsi' => 'Obat penurun panas dan pereda nyeri.',
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Amoxicillin 500mg',
                'harga' => 28000.00,
                'deskripsi' => 'Antibiotik untuk infeksi bakteri.',
                'stock' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Vitamin C 1000mg',
                'harga' => 25000.00,
                'deskripsi' => 'Suplemen untuk meningkatkan daya tahan tubuh.',
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
