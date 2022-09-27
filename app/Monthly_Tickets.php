<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;

class Monthly_Tickets extends Model
{
    
    protected $table = 'monthly_tickets';
    public $primaryKey = "id";
    protected $fillable = [
        'id', 'ticket_name','description','fee','status'
    ];

    protected $hidden = ['created_at','updated_at'];
    
}