<?php

namespace App\library;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

Class ApiFunctions
{
    
    public $okStatus = 200;
    public $newStatus = 201;
    public $badStatus = 400;
    public $authStatus = 401;
    public $forStatus = 403;
    public $notStatus = 404;
    public $serverStatus = 500;
    public function __construct()
	{
        
    }
  

    /**
     * encrypt_special_data function
     *
     * @param array $data
     * @param array $fields
     * @param string $type
     * @return void
     */
    public function esd($data=array(),$fields=array(),$type="array"){
        if($type == "array"){
            foreach ($data as $dkey => $dvalue) {
               if(in_array($dkey, $fields)){
                $data[$dkey] = en_de_crypt(strval($dvalue),'e');
               }
            }
        }
        if($type == "object"){
            $jdata = json_encode($data);
            $data = json_decode($jdata);
            foreach ($fields as $fkey => $fvalue) {
                if(isset($data->$fvalue)){
                    $data->$fvalue = strval(en_de_crypt(strval($data->$fvalue),'e'));
                }
            }
        }
        if($type == "aobject"){
            $jdata = json_encode($data);
            $data = json_decode($jdata);
            foreach ($data as $akey => $avalue) {
                if(!empty($fields)){
                    foreach ($fields as $fkey => $fvalue) {
                        if(isset($avalue->$fvalue)){
                            $avalue->$fvalue = en_de_crypt(strval($avalue->$fvalue),'e');
                        }
                    }
                }
                $data[$akey] = $avalue;
            }
        }
        if($type == "aarray"){
            foreach ($data as $akey => $avalue) {
                if(!empty($fields)){
                    foreach ($fields as $fkey => $fvalue) {
                        if(isset($avalue[$fvalue])){
                            $avalue[$fvalue] = en_de_crypt(strval($avalue[$fvalue]),'e');
                        }
                    }
                }
                $data[$akey] = $avalue;
            }
        }
        return $data;
    }


  public static function sendMessage($message, $recipients)
  {
      $account_sid = getenv("TWILIO_SID");
      $auth_token = getenv("TWILIO_AUTH_TOKEN");
      $twilio_number = getenv("TWILIO_NUMBER");
      $client = new Client($account_sid, $auth_token);     
      $data = $client->messages->create($recipients, 
              ['from' => $twilio_number, 'body' => $message] );
      return $data;
  }
}
