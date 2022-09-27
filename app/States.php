<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class States extends Model
{
    
    protected $table = 'states';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'name','country_id'
    ];

    
}