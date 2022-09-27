<?php

namespace App\library;

/**
 * fire base notification
 */
class FirebaseFunctions {

    // sending push message to single user by firebase reg id
    public function send($to, $message,$notification) {
        $fields = array(
            'to' => $to,
            'notification' => $notification,
             'data' => $message,
            "priority"  => "high"
        );
        return $this->sendPushNotification($fields);
       
    }
    // sending push message to single IOS user by firebase reg id
    public function sendToios($to, $message) {
        $fields = array(
            'to' => $to,
            'sound' => 'default',            
            'notification' => $message,
            // 'notification' => $message,
        );
        return $this->sendPushNotification($fields);
    }
    // Sending message to a topic by topic name
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
            // 'notification' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
      
            // 'notification' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers 
    private function sendPushNotification($fields) {
        
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'AAAANKPAKoY:APA91bGcGig1PvziGIPEmb2hEHucl1g-AMj06NtCsew5mojpGbRc7e9yHBA8o8p1ML-NhVvjSZK_ABimp6zEuP5Ef4vx52cGUXh8SqfLT0tO1rEhFW-F8WD2Q9X52t-e661Fphn8uq8B',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}
?>
