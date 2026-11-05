<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncedPuResult extends Model
{
    protected $fillable = [
        'result_id',
        'lga_name',
        'party_abbreviation',
        'party_score',
        'entered_by-user',
        'date_entered',
        'user_ip_address'
    ];

    protected $table = 'announced_pu_results';
    protected $primaryKey = 'result_id';

}
