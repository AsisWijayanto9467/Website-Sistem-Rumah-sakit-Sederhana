<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->foreignId('poliklinik_id')->constrained('polikliniks')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->time('waktu_kunjungan');
            $table->text('Alasan')->nullable();
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->enum('aksi', ['pending', 'approved', 'not approved'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
