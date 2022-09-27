<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Winner extends Model
{
    
    protected $table = 'winner';
    //public $primaryKey = "id";
    protected $fillable = [
        'id', 'draw_name','user_id','name','win_price','created_at','updated_at'
    ];

    
}