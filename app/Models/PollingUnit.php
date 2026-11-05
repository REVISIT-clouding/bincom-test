<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollingUnit extends Model
{
    protected $table = 'polling_unit';
    protected $primaryKey = 'polling_unit_id';

protected $fillable = [
        'polling_unit_uniqueid',
        'party_abbreviation',
        'party_score',
        'entered_by_user',
        'date_entered',
        'user_ip_address',
    ];
    
    public function wards(){
        return $this->belongsTo(Wards::class, 'ward_id', 'ward_id');
    }
    public function lga()
    {
        return $this->belongsTo(Lga::class, 'lga_id', 'lga_id');
    }

    // Relationship to Ward
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'ward_id');
    }
}
