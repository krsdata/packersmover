<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Banner extends Model
{
    
    protected $table = 'banner';
    //public $primaryKey = "id";
    protected $fillable = [
        'id', 'name','image','status','created_at','updated_at'
    ];

    
}