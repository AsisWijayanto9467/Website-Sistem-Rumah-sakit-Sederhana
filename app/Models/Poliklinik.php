<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    protected $table = "polikliniks";

    protected $fillable = [
        'nama_poli',
        'deskripsi',
        'status'
    ];


    public function doctors() {
        return $this->hasMany(Doctor::class);
    }
}
