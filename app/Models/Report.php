<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'visit_id',
        'report_file'
    ];

    public function visit() {
        return $this->belongsTo(Visits::class);
    }
}
