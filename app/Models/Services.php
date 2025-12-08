<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'nama_klinik',
        'tipe_layanan',
        'harga',
        'deskripsi'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    public function visits() {
        return $this->hasMany(Visits::class);
    }

}
