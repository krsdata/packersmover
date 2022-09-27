<?php

            namespace App;
            use Illuminate\Database\Eloquent\Model;
            use App\Traits\Encryptable;

              class Date_details extends Model
              {
                  
                  protected $table = "date_details";
                  public $primaryKey = "id";
                  protected $fillable = [
                      "id, updated_at, created_at, fuser_id, tuser_id, lat, lng, start_time, end_time, start_date, end_date, location_name, status"
                  ];


              }

