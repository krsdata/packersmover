<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Order_master extends Model
{
    
    protected $table = 'order_master';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'name','email','contact','address','date','item_name'
    ];

    protected $hidden = ['created_at','updated_at'];
    
}