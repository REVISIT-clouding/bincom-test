<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $primarykey = 'state_id';

    public function states(){
       return $this->hasMany(Lga::class);
    }
}
