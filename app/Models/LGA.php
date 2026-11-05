<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lga extends Model
{
    protected $table = 'lga';
    protected $primaryKey = 'lga_id';

    public function states(){
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }
    public function wards(){
        return $this->hasMany(Ward::class, 'ward_id', 'ward_id');
    }
}
