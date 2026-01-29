<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitDetails extends Model
{
    protected $table = 'visit_details';

    protected $fillable = [
        'visit_id',
        'diagnosis',
        'layanan',
        'notes',
    ];

    public function visit() {
        return $this->belongsTo(Visits::class, 'visit_id', 'id');
    }

    public function medications()
    {
        return $this->belongsToMany(
            Medications::class,
            'visit_detail_medications',   
            'visit_detail_id',            
            'medication_id'               
        )->withPivot('quantity', 'aturan_pakai')
        ->withTimestamps();
    }
}
