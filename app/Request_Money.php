<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Request_Money extends Model
{
 
    protected $table = 'request_money';
    //public $primaryKey = "id";
    //public $incrementing = false;
    protected $fillable = [
        'user_id','account_number','branch_code','amount','contact'
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}