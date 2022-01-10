<?php

namespace App\Services;


use paytm\paytmchecksum\PaytmChecksum;

class Razorpay
{


    public static function createLink($paramArray = [])
    {
        $link =  '/v1/payout-links';
        $parameters = [];
        $parameters["account_number"] = config("payment.RAZORPAY_ACC_NO");
        $parameters["contact"] = [
            "name" => $paramArray["name"], "email" => $paramArray["beneficiaryEmail"], "contact" => $paramArray["beneficiaryPhoneNo"], "type" => "Taster"
        ];
        $parameters["amount"] = ($paramArray["amount"] * 100);
        $parameters["currency"] = "INR";
        $parameters["purpose"] = "payout";
        $parameters["description"] = $paramArray["comments"];
        $parameters["send_sms"] = true;
        $parameters["send_email"] = true;
        $parameters["expire_by"] = strtotime(date("Y-m-d", strtotime("+10 days")));
        $parameters["receipt"] = $paramArray["orderId"];

        $post_data = json_encode($parameters, JSON_UNESCAPED_SLASHES);

        $url = config("payment.RAZORPAY_ENDPOINT") . $link;
        print_r($post_data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Basic " . base64_encode(config("payment.RAZORPAY_KEY_ID") . ":" . config("payment.RAZORPAY_KEY_SECRET"))));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        echo $response =   curl_exec($ch);
        echo "<br/>";
        echo $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $returnResp = [];
        $returnResp["statusCode"] = $http_status;
        if (is_string($response)) {
            $re = json_decode($response, true);
        } else if (is_array($response)) {
            $re  = $response;
        }


        if ($http_status == 200) {
            $returnResp["result"] = $re;
            $returnResp["status"] = "SUCCESS";
            $returnResp['statusMessage'] = "Transaction Successfull";
            $returnResp["result"]["expiryDate"] = gmdate("Y-m-d", strtotime($re["expire_by"]));
            $returnResp["result"]["payoutLinkId"] = $re["id"];
            $returnResp["result"]["payoutLink"] = $re["short_url"];
        } else {
            $returnResp["status"] = "FAILURE";
            $returnResp['statusMessage'] = (isset($re["error"]["description"]) ?  $re["error"]["description"] : "Transaction Failure");
            $returnResp["result"] = $re["error"];
        }
        return $returnResp;
    }

    public static function getStatus($Txn_Id)
    {
        $link = '/v1/payout-links';

        $params = ["orderId" => $Txn_Id->payout_link_id];
        $post_data = json_encode($params, JSON_UNESCAPED_SLASHES);

        $checksum = PaytmChecksum::generateSignature($post_data, config("payment.PAYTM_MERCHANT_KEY"));

        $x_mid      = config("payment.PAYTM_MID");
        $x_checksum = $checksum;


        $url = config("payment.PAYTM_ENDPOINT") . $link;


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Basic " . base64_encode(config("payment.RAZORPAY_KEY_ID") . ":" . config("payment.RAZORPAY_KEY_SECRET"))));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $returnResp = [];
        $returnResp["statusCode"] = $http_status;
        if (is_string($response)) {
            $re = json_decode($response, true);
        } else if (is_array($response)) {
            $re  = $response;
        }
        $returnResp["result"] = $re;

        if ($http_status == 200) {
            if ($returnResp["status"] == "processed") {

                $returnResp["status"] = "SUCCESS";
            } else if ($returnResp["status"] == "issued") {

                $returnResp["status"] = "PENDING";
            } else if ($returnResp["status"] == "processing") {

                $returnResp["status"] = "PENDING";
            } else if ($returnResp["status"] == "attempted") {

                $returnResp["status"] = "PENDING";
            } else if ($returnResp["status"] == "cancelled") {

                $returnResp["status"] = "CANCELLED";
            } else if ($returnResp["status"] == "expired") {

                $returnResp["status"] = "EXPIRED";
            }

            $returnResp['statusMessage'] = "Transaction Successfull";
            $returnResp["result"]["expiryDate"] = gmdate("Y-m-d", strtotime($returnResp["expire_by"]));
            $returnResp["result"]["payoutLinkId"] = $returnResp["id"];              
        } else {
            // $returnResp["status"] = "FAILURE";
            $returnResp['statusMessage'] = (isset($re["description"]) ?  $re["description"] : "Transaction Failure");
        }
    }

    public function processCallback($request)
    {

        if (isset($request->event)) {
            if ($request->event == "payout_link.processed") {

                $returnResp["status"] = "SUCCESS";
            } else if ($request->event == "payout_link.issued") {

                $returnResp["status"] = "PENDING";
            } else if ($request->event == "payout_link.processing") {

                $returnResp["status"] = "PENDING";
            } else if ($request->event == "payout_link.attempted") {

                $returnResp["status"] = "PENDING";
            } else if ($request->event == "payout_link.cancelled") {

                $returnResp["status"] = "CANCELLED";
            } else if ($request->event == "payout_link.expired") {

                $returnResp["status"] = "EXPIRED";
            }
        }
        return ["orderId" => $request->payload->payout_link->entity->receipt, "status" => $returnResp["status"]];
    }
}
