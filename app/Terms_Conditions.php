<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Terms_Conditions extends Model
{
 
    protected $table = 'terms_conditions';
    //public $primaryKey = "id";
    //public $incrementing = false;
    protected $fillable = [
        'name','description'
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}