<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Otp_Verify extends Model
{
 
    protected $table = 'otp_verify';
    //public $primaryKey = "id";
    //public $incrementing = false;
    protected $fillable = [
        'contact','otp','user_id'
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}