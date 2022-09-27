<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Tables extends Model
{
    
    protected $table = 'c_tables';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'table_name'
    ];

    
}