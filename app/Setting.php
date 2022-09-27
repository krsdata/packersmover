<?php
                        namespace App;
                        use Illuminate\Database\Eloquent\Model;
                        use App\Traits\Encryptable;

                          class Setting extends Model
                          {
                              
                              protected $table = "setting";
                              public $primaryKey = "id";
                              protected $fillable = [
                                  "time"
                              ];

    
                          }