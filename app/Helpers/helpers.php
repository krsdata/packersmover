<?php // Code within app\Helpers\Helper.php
namespace App\Helpers;
use Config;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class Helper
{
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
