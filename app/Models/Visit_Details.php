<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit_Details extends Model
{
    protected $table = 'visit_details';

    protected $fillable = [
        'visit_id',
        'diagnosis',
        'layanan',
        'notes',
        'medication_id',
        'quantity',
    ];

    public function visit() {
        return $this->belongsTo(Visits::class);
    }

    public function medication() {
        return $this->belongsTo(Medications::class);
    }
}
