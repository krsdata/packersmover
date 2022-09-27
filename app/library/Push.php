<?php

namespace App\library;
/**
 * Push notification file 
 */
class Push {
    private $title;
    private $message;
    private $od_id;
    private $to_id;
    private $fname;
    private $uid;
    private $data;

    function __construct() {
        
    }

    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setoid($to_id) {
        $this->to_id = $to_id;
    }

    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function setfname($fname) {
        $this->fname = $fname;
    }


    public function setProfile($od_id) {
        $this->od_id = $od_id;
    }

    public function setUser($uid) {
        $this->uid = $uid;
    }

    public function setPayload($data) {
        $this->data = $data;
    }
    
     public function setClick_action($click_action) {
        $this->click_action = $click_action;
    }

    public function getPush() {
        $res = array();
        $res1 = array();
         
        $res1['fname'] = $this->fname;
        $res1['to_id'] = $this->to_id;
        $res1['uid'] = $this->uid;
        $res1['message'] = $this->message;
        
        //  $res['body'] = $this->message;
        
        $res['title'] = $this->title;
        // $res['body'] = json_encode($res1);
        $res['body'] = $this->message;
       
        return $res;
    }
    
    public function getPush1()
    {
        $res = array();
        $res['body'] = $this->message;
        
        $res['title'] = $this->title;
        $res['click_action'] = $this->click_action;
       
        return $res;
    }

}