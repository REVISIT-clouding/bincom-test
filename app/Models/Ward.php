<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'ward';
    protected $primaryKey = 'ward_id';

    public function polling_units() {
        return $this->hasMany(PollingUnit::class, 'polling_unit_id', 'polling_unit_id');
    }

    public function lga(){
        return $this->belongsTo(Lga::class, 'lga_id', 'lga_id');
    }
}