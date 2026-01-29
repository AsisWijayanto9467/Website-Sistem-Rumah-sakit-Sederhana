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
        Schema::create('visit_detail_medications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_detail_id')->constrained('visit_details')->OnDelete('cascade');
            $table->foreignId('medication_id')->constrained('medications')->OnDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->string('aturan_pakai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_detail_medications');
    }
};
