<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Booking_Draw extends Model
{
    
    protected $table = 'booking_draw';
    public $primaryKey = "id";
    protected $fillable = [
        'id','type','first_name','last_name','gender','city','state','country','mobile','draw_name','fees','card_name',
        'card_number','month','year','cvv','created_at','updated_at'
    ];

    //protected $hidden = ['created_at','updated_at'];
    
}