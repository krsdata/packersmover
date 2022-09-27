<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Feedback extends Model
{
    
    protected $table = 'feedback';
    //public $primaryKey = "id";
    protected $fillable = [
        'id', 'rating','user_id','review','created_at','updated_at'
    ];

    
}