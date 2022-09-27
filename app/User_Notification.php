<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class User_Notification extends Model
{
 
    protected $table = 'user_notification';
    public $primaryKey = "id";
    protected $fillable = [
        'id','title','message','user_id',
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}