<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Draw_Text extends Model
{
    
    protected $table = 'draw_text';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'type','type_title','title','description','text_type'
    ];

    protected $hidden = ['created_at','updated_at'];
    
}