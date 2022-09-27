<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Countries extends Model
{
    
    protected $table = 'countries';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'sortname','name','phonecode'
    ];

    
}