<?php

namespace App\Services;


use paytm\paytmchecksum\PaytmChecksum;

class Cashfree
{
    
    public  static  function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function create_header($token){
        $headers = [
            'X-Client-Id: '.config("payment.CASHFREE_CLIENT_ID"),
            'X-Client-Secret: '.config("payment.CASHFREE_SECRET_ID"), 
            'Content-Type: application/json',
        ];
        if(!is_null($token)){
            array_push($headers, 'Authorization: Bearer '.$token);
        }
        return $headers;
    }

   
    #get auth token
    public static function getToken(){
        
        $finalUrl = config("payment.CASHFREE_ENDPOINT")."/payout/v1/authorize";
        $headers = create_header(null);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        $r = curl_exec($ch);
        
        if(curl_errno($ch)){
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);    
        if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: '.$rObj['message']);
        $response = $rObj;
        return $response['data']['token'];
    
    } 

    public static function createLink($paramArray = [])
    {
        $data["email"] =   $paramArray["email"];
        $data["phone"] =   $paramArray["phone"];
        $data["amount"] =   $paramArray["amount"];
        $data["name"] =   $paramArray["name"];
        $data["linkExpiry"] = "2021/12/25";
        $data["cashgramId"] = generateRandomString(15);
        $token = getToken(); 
        
            $finalUrl = config("payment.CASHFREE_ENDPOINT")."/payout/v1/createCashgram";
            $headers = create_header($token);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $finalUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
            if(!is_null($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
            
            $r = curl_exec($ch);
            
            if(curl_errno($ch)){
                print('error in posting');
                print(curl_error($ch));
                die();
            }
            curl_close($ch);
            $rObj = json_decode($r, true);    
            if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: '.$rObj['message']);
            $rObj["result"]["expiryDate"] = $data["linkExpiry"];
            $rObj["result"]["payout_link_id"] = $data["cashgramId"];
            unset($rObj["data"]);
            $r = json_encode($rObj, true); 

            return $r; 
       
    }

    public static function getStatus($cashgramId)
    {
         
            $token = getToken();
            $query_string = "?cashgramId=".$cashgramId;
            $finalUrl = config("payment.CASHFREE_ENDPOINT")."/payout/v1/getCashgramStatus".$query_string;
            $headers = create_header($token);
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_URL, $finalUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
            
            $r = curl_exec($ch);
            
            if(curl_errno($ch)){
                print('error in posting');
                print(curl_error($ch));
                die();
            }
            curl_close($ch);
            $rObj = json_decode($r, true);    
            if($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: '.$rObj['message']);
            $rObj["result"]["payoutLink"] = $rObj["data"]["cashgramLink"];
            $rObj["result"]["payoutLinkId"] = $rObj["data"]["cashgramId"];
            $rObj["result"]["payoutLinkStatus"] = $rObj["data"]["cashgramStatus"];
            unset($rObj["data"]);
            $r = json_encode($rObj, true); 

            return $r; 
        
    }
}
