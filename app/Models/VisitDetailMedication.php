<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitDetailMedication extends Model
{
    protected $table = 'visit_detail_medications';

    protected $fillable = [
        'visit_detail_id',
        'medication_id',
        'quantity',
        'aturan_pakai',
    ];
}
