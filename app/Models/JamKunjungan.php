<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamKunjungan extends Model
{
    protected $table = 'jam_kunjungan';

    protected $fillable = [
        'dokter_id',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'dokter_id');
    }
}
