<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Draw extends Model
{
    
    protected $table = 'draw';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'draw_name','type','ticket_id','text','prize','entry_fee','t_no','start_date','end_date','status','how_to_play'
    ];

    protected $hidden = ['created_at','updated_at'];
    
}