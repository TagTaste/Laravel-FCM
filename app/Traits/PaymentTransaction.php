<?php

namespace App\Traits;

use App\Events\Actions\PaymentTransactionCreate;
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
            
            $pay["orderId"] = $data["transaction_id"];
            $pay["amount"] = $data["amount"];
            $pay["beneficiaryPhoneNo"] = $data["phone"];
            $pay["beneficiaryEmail"] = $data["email"];
            $pay["notifyMode"] = ["SMS", "EMAIL"];
            if($data["model_type"] == "Private Review" || $data["model_type"] == "Public Review"){
                $pay["subwalletGuid"] = config("payment.PAYTM_GUID_TASTING");
                $pay["comments"] = $data["comment"] ?? "Remuneration for reviewing a product on TagTaste.";
            }else if($data["model_type"] == "Survey"){
                $pay["subwalletGuid"] = config("payment.PAYTM_GUID_SURVEY");
                $pay["comments"] = $data["comment"] ?? "Remuneration for taking a survey on TagTaste.";
            }else{
                $pay["subwalletGuid"] = config("payment.PAYTM_GUID_TASTING");
                $pay["comments"] = $data["comment"] ?? "Payment from Tagtaste.";
            }
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
                    $dataToUpdate = ["expired_at" => date("Y-m-d H:i:s", strtotime($resp["result"]["expiryDate"])), "payout_link_id" => $resp["result"]["payoutLinkId"], "status_json" => json_encode($resp), "status_id" => config("constant.PAYMENT_PENDING_STATUS_ID")];
                    event(new PaymentTransactionCreate($data,null,["title"=>"Payment Link Generated"]));
                    return PaymentLinks::where("transaction_id", $resp["result"]["orderId"])->update($dataToUpdate);
                } else {
                    PaymentLinks::where("transaction_id", $data["transaction_id"])->update(["status_json" => json_encode($resp)]);
                    return false;
                }
            }
        }
    }

    public function getStatus($transaction_id)
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
                if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "SUCCESS") {
                    $data["status_id"] = config("constant.PAYMENT_SUCCESS_STATUS_ID");
                } else if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "FAILURE") {
                    $data["status_id"] = config("constant.PAYMENT_FAILURE_STATUS_ID");
                } else if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "CANCELLED") {
                    $data["status_id"] = config("constant.PAYMENT_CANCELLED_STATUS_ID");
                } else if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "EXPIRED") {
                    $data["status_id"] = config("constant.PAYMENT_EXPIRED_STATUS_ID");
                }
                return PaymentLinks::where("transaction_id", $transaction_id)->update($data);
            } else {
                return PaymentLinks::where("transaction_id", $transaction_id)->update(["status_json" => json_encode($resp)]);
            }
        }
    }

    public function callback(Request $request)
    {
        $inputs = $request->all();
        $dataStr = json_encode($inputs);
        file_put_contents(storage_path("logs/") ."paytm_callback_logs.txt", $dataStr, FILE_APPEND);
        file_put_contents(storage_path("logs/") ."paytm_callback_logs.txt", "\n++++++++++++++++++++++\n", FILE_APPEND);        

        if ($request->has("status") && $request->has("result") && !empty($request->result["orderId"])) {
            $resp = $request->all();
            $data = ["status_json" => json_encode($resp)];
            if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "SUCCESS") {
                $data["status_id"] = config("constant.PAYMENT_SUCCESS_STATUS_ID");
            } else if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "FAILURE") {
                $data["status_id"] = config("constant.PAYMENT_FAILURE_STATUS_ID");
            } else if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "CANCELLED") {
                $data["status_id"] = config("constant.PAYMENT_CANCELLED_STATUS_ID");
            } else if (isset($resp["result"]["payoutLinkStatus"]) && $resp["result"]["payoutLinkStatus"] == "EXPIRED") {
                $data["status_id"] = config("constant.PAYMENT_EXPIRED_STATUS_ID");
            }
            file_put_contents(storage_path("logs/") ."paytm_callback_logs.txt", "\n-----------------SAVING DATA -------------------\n\n\n", FILE_APPEND);        
            return ["status" => PaymentLinks::where("transaction_id", $resp["result"]["orderId"])->update($data)];
        }
        return ["status" => false];
    }
}
