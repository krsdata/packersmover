<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order_master;

class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Booking quotation date before message send';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

         //1201159438906763690
        // Booking date tommorrow so send before msg
        
        $prev_day = date('Y-m-d h:i:s', strtotime(' -1 day'));
        $nxt_day = date('Y-m-d', strtotime(' +1 day'));
  
        $booking_reminder = Order_master::where('date',$nxt_day)->get();
        $counts = array();
       foreach($booking_reminder as $bkey => $bval)
       {
          $counts[$bkey]['mobiles'] = '91'.$bval->contact;
       }
       
        $flow_id = "6040ca63367591043f1a4183";
        $senderId = "SAIPKR";
       
        //Prepare you post parameters
        $postData = array(
            "flow_id" => $flow_id,
            "sender" => $senderId,
            "recipients" => $counts
        );

        $postDataJson = json_encode($postData);
        $url="http://api.msg91.com/api/v5/flow/";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postDataJson,
            CURLOPT_HTTPHEADER => array(
                "authkey: 238708A7BIRMsept5ba3c275",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     echo $response;
        // }

    }
}
