<?php

namespace App\Services;


use paytm\paytmchecksum\PaytmChecksum;
use Exception;

class Cashfree
{

    public static function create_header($token)
    {
        $headers = [
            'X-Client-Id: ' . config("payment.CASHFREE_CLIENT_ID"),
            'X-Client-Secret: ' . config("payment.CASHFREE_SECRET_ID"),
            'Content-Type: application/json',
        ];
        if (!is_null($token)) {
            array_push($headers, 'Authorization: Bearer ' . $token);
        }
        return $headers;
    }


    #get auth token
    public static function getToken()
    {

        $finalUrl = config("payment.CASHFREE_ENDPOINT") . "/payout/v1/authorize";
        $headers = self::create_header(null);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        $r = curl_exec($ch);

        if (curl_errno($ch)) {
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);
        if ($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: ' . $rObj['message']);
        $response = $rObj;
        return $response['data']['token'];
    }

    public static function createLink($paramArray = [])
    {
        $data["email"] = $paramArray["beneficiaryEmail"];
        $data["phone"] = $paramArray["beneficiaryPhoneNo"];
        $data["amount"] = $paramArray["amount"];
        $data["name"] = $paramArray["name"];
        $data["linkExpiry"] = date("Y/m/d", strtotime("+10 days"));
        $data["cashgramId"] = $paramArray["orderId"];
        $token = self::getToken();

        $finalUrl = config("payment.CASHFREE_ENDPOINT") . "/payout/v1/createCashgram";
        $headers = self::create_header($token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        if (!is_null($data)) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $r = curl_exec($ch);

        if (curl_errno($ch)) {
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);
        if ($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: ' . $rObj['message']);
        $rObj["result"]["expiryDate"] = $data["linkExpiry"];
        $rObj["result"]["payoutLinkId"] = $paramArray["orderId"];
        unset($rObj["data"]);
        $r = json_encode($rObj, true);

        return $r;
    }

    public static function getStatus($cashgramId)
    {
        $token = self::getToken();
        $query_string = "?cashgramId=" . $cashgramId->transaction_id;
        $finalUrl = config("payment.CASHFREE_ENDPOINT") . "/payout/v1/getCashgramStatus" . $query_string;
        $headers = self::create_header($token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);

        $r = curl_exec($ch);

        if (curl_errno($ch)) {
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);
        $rObj = json_decode($r, true);
        if ($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200') throw new Exception('incorrect response: ' . $rObj['message']);
        $rObj["result"]["payoutLink"] = $rObj["data"]["cashgramLink"];
        $rObj["result"]["payoutLinkId"] = $cashgramId;
        $rObj["result"]["payoutLinkStatus"] = $rObj["data"]["cashgramStatus"];
        unset($rObj["data"]);
        $r = json_encode($rObj, true);

        return $r;
    }

    public static function processCallback($request)
    {
        
        if($request->has('event') && $request->event=="CASHGRAM_REDEEMED"){
            $status = "SUCCESS";
        }else if($request->has('event') && $request->event=="CASHGRAM_TRANSFER_REVERSAL"){
            $status = "FAILURE";
        }else if($request->has('event') && $request->event=="CASHGRAM_EXPIRED"){
            $status = "EXPIRED";
        }
        return ["orderId" => $request->cashgramid, "status" => $status];
    }
}
