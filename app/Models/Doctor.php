<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'nama',
        'poliklinik_id',
        'tarif_konsultasi',
        'lama_pengalaman',
        'pendidikan',
        'nomor_telpon',
        'status'
    ];


    
    public function poliklinik() {
        return $this->belongsTo(Poliklinik::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function visits() {
        return $this->hasMany(Visits::class);
    }

    public function jamKunjungans()
    {
        return $this->hasMany(JamKunjungan::class);
    }

    public function patients()
    {
        return $this->belongsToMany(
            Patients::class,
            'visits',
            'doctor_id',
            'patient_id'
        )->distinct();
    }
}
