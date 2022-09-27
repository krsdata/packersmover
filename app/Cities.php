<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Cities extends Model
{
    
    protected $table = 'cities';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'name','state_id'
    ];

    
}