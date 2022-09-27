<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Faq extends Model
{
 
    protected $table = 'faq';
    public $primaryKey = "id";
    //public $incrementing = false;
    protected $fillable = [
        'id','title','description','status','lang',
    ];

    protected $hidden = ['created_at','deleted_at','updated_at'];
}