<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visits extends Model
{
    protected $table = 'visits';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'tanggal_kunjungan',
        'waktu_kunjungan',
        'complaint',
        'status',
    ];

    protected $casts = [
        "tanggal_kunjungan" => 'date',
        'waktu_kunjungan' => 'datetime:H:i'
    ];


    public function patient() {
        return $this->belongsTo(Patients::class);
    }
    public function doctor() {
        return $this->belongsTo(Doctors::class);
    }
    public function service() {
        return $this->belongsTo(Services::class);
    }

    public function details() {
        return $this->hasMany(Visit_Details::class);
    }

    public function raport() {
        return $this->hasOne(Report::class);
    }


}
