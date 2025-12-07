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
}
