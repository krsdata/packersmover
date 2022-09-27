<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Contact_Us extends Model
{
 
    protected $table = 'contact_us';
    //public $primaryKey = "id";
    //public $incrementing = false;
    protected $fillable = [
        'name','email_id','mobile_no','message','type'
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}