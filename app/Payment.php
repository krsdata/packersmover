<?php

            namespace App;
            use Illuminate\Database\Eloquent\Model;
            use App\Traits\Encryptable;

              class Payment extends Model
              {
                  
                  protected $table = "payment";
                  public $primaryKey = "id";
                  protected $fillable = [
                      "id, updated_at, created_at, user_id, p_type, amount"
                  ];


              }

