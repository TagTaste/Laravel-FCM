<?php

namespace App\Services;


use paytm\paytmchecksum\PaytmChecksum;

class Paytm
{


    public static function createLink($paramArray = [])
    {
        $link =  '/pls/api/v1/payout-link/create';
        $paramArray["callbackUrl"] = config("payment.PAYTM_CALLBACK_URL");

        $post_data = json_encode($paramArray, JSON_UNESCAPED_SLASHES);

        $checksum = PaytmChecksum::generateSignature($post_data, config("payment.PAYTM_MERCHANT_KEY"));

        $x_mid      = config("payment.PAYTM_MID");
        $x_checksum = $checksum;

        /* for Staging */
        $url = config("payment.PAYTM_ENDPOINT") . $link;
        print_r($post_data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response =   curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function getStatus($Txn_Id)
    {
        $link = '/pls/api/v2/payout-link/fetch';

        $params = ["orderId" => $Txn_Id];
        $post_data = json_encode($params, JSON_UNESCAPED_SLASHES);

        $checksum = PaytmChecksum::generateSignature($post_data, config("payment.PAYTM_MERCHANT_KEY"));

        $x_mid      = config("payment.PAYTM_MID");
        $x_checksum = $checksum;


        $url = config("payment.PAYTM_ENDPOINT") . $link;


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
