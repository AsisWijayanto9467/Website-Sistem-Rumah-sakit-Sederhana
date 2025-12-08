<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medications extends Model
{
    protected $table = 'medications';
    
    protected $fillable = [
        'nama',
        'harga',
        'deskripsi',
        'stock',
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    public function visitDetails() {
        return $this->hasMany(Visit_Details::class);
    }
}
