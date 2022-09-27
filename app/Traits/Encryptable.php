<?php

namespace App\Traits;
use Crypt;

trait Encryptable
{

     public function toArray()
     {
        $array = parent::toArray();
        foreach ($array as $key => $attribute) {
            if (in_array($key, $this->encryptable) && $array[$key]!='') {
                try {
                $array[$key] = Crypt::decrypt($array[$key]);
               } catch (\Exception $e) {
               }
            }
        }
        return $array;
    }

    public function getAttribute($key)
    {
        try {
            $value = parent::getAttribute($key);
            if (in_array($key, $this->encryptable) && $value!='') {
                $value = Crypt::decrypt($value);
                return $value;
            }
            return $value;
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }
}