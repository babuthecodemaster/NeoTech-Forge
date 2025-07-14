<?php
class PaytmChecksum {
    private static $iv = "@@@@&&&&####$$$$";

    static public function encrypt($input, $key) {
        $key = html_entity_decode($key);
        return openssl_encrypt($input, "AES-128-CBC", $key, 0, self::$iv);
    }

    static public function decrypt($encrypted, $key) {
        $key = html_entity_decode($key);
        return openssl_decrypt($encrypted, "AES-128-CBC", $key, 0, self::$iv);
    }

    static public function generateSignature($params, $key) {
        if(!is_array($params) && !is_string($params)){
            throw new Exception("string or array expected, " . gettype($params) . " given");			
        }
        if(is_array($params)){
            $params = self::getStringByParams($params);
            
        }
        return self::generateSignatureByString($params, $key);
    }

    static public function verifySignature($params, $key, $checksum){
        if(!is_array($params) && !is_string($params)){
            throw new Exception("string or array expected, " . gettype($params) . " given");
        }
        if(is_array($params)){
            $params = self::getStringByParams($params);
        }		
        return self::verifySignatureByString($params, $key, $checksum);
    }

    static private function generateSignatureByString($params, $key){
        $salt = self::generateRandomString(4);
        return self::calculateChecksum($params, $key, $salt);
    }

    static private function verifySignatureByString($params, $key, $checksum){
        $paytm_hash = self::decrypt($checksum, $key);
        $salt = substr($paytm_hash, -4);
        return $paytm_hash == self::calculateChecksum($params, $key, $salt);
    }

    static private function generateRandomString($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "9876543210ZYXWVUTSRQPONMLKJIHGFEDCBAabcdefghijklmnopqrstuvwxyz!@#$&_";	
        for($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    static private function getStringByParams($params) {
        ksort($params);
        $params = array_map(function ($value){
            return ($value !== null && strtolower($value) !== "null") ? $value : "";
        }, $params);
        return implode("|", $params);
    }

    static private function calculateChecksum($params, $key, $salt){
        $finalString = $params . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = self::encrypt($hashString, $key);
        return $checksum;
    }
}