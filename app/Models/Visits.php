<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
    protected $table = 'visits';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'poliklinik_id',
        'tanggal_kunjungan',
        'waktu_kunjungan',
        'Alasan',
        'status',
        'aksi',
    ];

    protected $casts = [
        "tanggal_kunjungan" => 'date',
        'waktu_kunjungan' => 'datetime:H:i'
    ];


    public function patient() {
        return $this->belongsTo(Patients::class);
    }
    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
    public function poliklinik() {
        return $this->belongsTo(Poliklinik::class);
    }

    public function details() {
        return $this->hasOne(VisitDetails::class, 'visit_id', 'id');
    }

    public function raport() {
        return $this->hasOne(Report::class);
    }


}
