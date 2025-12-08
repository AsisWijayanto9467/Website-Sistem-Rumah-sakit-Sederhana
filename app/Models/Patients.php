<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'user_id',
        'nama',
        'nomor_telpon',
        'gender',
        'tipe_darah',
        'tanggal_lahir',
        'alamat',
        'tanggal_registrasi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_registrasi' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function visits() {
        return $this->hasMany(Visits::class);
    }
}
