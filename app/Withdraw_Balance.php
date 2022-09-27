<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Withdraw_Balance extends Model
{
    
    protected $table = 'withdraw_balance';
    //public $primaryKey = "id";
    protected $fillable = [
        'id', 'user_id','withdraw_amount','status','account_number','created_at','updated_at'
    ];

    
}