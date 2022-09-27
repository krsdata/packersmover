<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Privacy_Policy extends Model
{
 
    protected $table = 'privacy_policy';
    //public $primaryKey = "id";
    //public $incrementing = false;
    protected $fillable = [
        'name','description','lang'
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}