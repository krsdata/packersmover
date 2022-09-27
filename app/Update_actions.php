<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Encryptable;
class Update_actions extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
	/*use Encryptable;

    protected $encryptable = [
        'o_name','o_address','o_address_type','o_country','o_type','o_user','o_contact'
    ];*/
    
    protected $table = 'update_actions';
    public $primaryKey = "id";
    public $fillable = ['filename','upload_path','store_path','status','comments'];
}