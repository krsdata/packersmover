<?php
                        namespace App;
                        use Illuminate\Database\Eloquent\Model;
                        use App\Traits\Encryptable;

                          class Membership extends Model
                          {
                              
                              protected $table = "membership";
                              public $primaryKey = "id";
                              protected $fillable = [
                                  "name","price"
                              ];

    
                          }