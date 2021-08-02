<?php

namespace App\Traits;

use App\Payment\PaymentLinks;
use Illuminate\Http\Request;
use paytm\paytmchecksum\PaytmChecksum;

trait PaymentTransaction
{
    public function createLink($data)
    {
        $link = '/pls/api/v1/payout-link/create';
        if (isset($data["transaction_id"]) && isset($data["phone"]) && isset($data["email"]) && isset($data["amount"]) && isset($data["title"])) {
            $pay = [];
            $pay["subwalletGuid"] = config("payment.PAYTM_GUID");
            $pay["orderId"] = $data["transaction_id"];
            $pay["amount"] = $data["amount"];
            $pay["beneficiaryPhoneNo"] = $data["phone"];
            $pay["beneficiaryEmail"] = $data["email"];
            $pay["notifyMode"] = ["SMS", "EMAIL"];
            $pay["comments"] = "Payout Link For " . $data["model_type"] . " - " . $data["title"];
            $pay["callbackUrl"] = config("payment.PAYTM_CALLBACK_URL");

            $post_data = json_encode($pay, JSON_UNESCAPED_SLASHES);

            $checksum = PaytmChecksum::generateSignature($post_data, config("payment.PAYTM_MERCHANT_KEY"));

            $x_mid      = config("payment.PAYTM_MID");
            $x_checksum = $checksum;

            /* for Staging */
            $url = config("payment.PAYTM_ENDPOINT") . $link;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            
            if (!empty($response)) {
                $resp = $response;
                if (!is_array($response)) {
                    $resp = json_decode($response, true);
                }
                if ($resp["status"] == "SUCCESS") {
                    return PaymentLinks::where("transaction_id", $resp["result"]["orderId"])->update(["expired_at" => date("Y-m-d H:i:s", strtotime($resp["result"]["expiryDate"])), "payout_link_id" => $resp["result"]["payoutLinkId"], "status_json" => json_encode($resp)]);
                } else {
                    PaymentLinks::where("transaction_id", $data["transaction_id"])->update(["status_json" => json_encode(["status" => "Failed to create link"])]);
                    return false;
                }
            }
        }
    }

    public function getStatus($transaction_id, Request $request)
    {
        $link = '/pls/api/v2/payout-link/fetch';
        $paytmParams = [];

        $paytmParams["orderId"]  = $transaction_id;

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
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


        if (!empty($response)) {
            $resp = $response;
            if (!is_array($response)) {
                $resp = json_decode($response, true);
            }
            if ($resp["status"] == "SUCCESS") {
                $data = ["link" => $resp["result"]["payoutLink"], "payout_link_id" => $resp["result"]["payoutLinkId"], "status_json" => json_encode($resp)];
                if (isset($resp["paymentDetails"]["status"]) && $resp["paymentDetails"]["status"] == "SUCCESS") {
                    $data["status_id"] = config("constant.PAYMENT_SUCCESS_STATUS_ID");
                } else {
                    $data["status_id"] = config("constant.PAYMENT_PENDING_STATUS_ID");
                }
                return PaymentLinks::where("transaction_id", $resp["result"]["orderId"])->update($data);
            } else {
                return PaymentLinks::where("transaction_id", $resp["result"]["orderId"])->update(["status_json" => json_encode($resp)]);
            }
        }
    }
}
