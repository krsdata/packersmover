<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Category extends Model
{
    
    protected $table = "category";
   // public $incrementing = false;
    public $primaryKey = "id";
    protected $fillable = [
        "name", "image", "description",'image','parent_id','updated_at'
    ];

    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at','deleted_at'];

}
