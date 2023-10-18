<?php

namespace App\Traits;

use App\Collaborate;
use App\Deeplink;
use App\Events\Actions\PaymentTransactionCreate;
use App\Events\Actions\PaymentTransactionStatus;
use App\Payment\PaymentLinks;
use App\PublicReviewProduct;
use App\Surveys;
use Exception;
use Illuminate\Http\Request;
use App\Mail\TdsDeductionFinanceMail;
use Carbon\Carbon;

trait PaymentTransaction
{
    public function createLink($data)
    {

        $paymentChannel = config("app.payment_channel");

        if (isset($data["transaction_id"]) && isset($data["phone"]) && isset($data["email"]) && isset($data["amount"]) && isset($data["title"])) {
            $pay = [];
            $pay["orderId"] = $data["transaction_id"];
            $pay["amount"] = $data["amount"];
            $pay["payout_amount"] = $data["payout_amount"];
            $pay["beneficiaryPhoneNo"] = $data["phone"];
            $pay["beneficiaryEmail"] = $data["email"];

            $pay["name"] = $data["name"];
            $pay["notifyMode"] = ["SMS", "EMAIL"];
            if ($data["model_type"] == "Private Review" || $data["model_type"] == "Public Review") {
                if ($data["model_type"] == "Private Review") {
                    $getName = Collaborate::where("id", $data["model_id"])->first();
                    $name = $getName->title ?? "";
                    $hyperlink = '<a href="' . Deeplink::getShortLink('collaborate', $data["model_id"]) . '">' . $name . '</a>';
                    $type = "Collaboration";
                } else if ($data["model_type"] == "Public Review") {
                    $getName = PublicReviewProduct::where("id", $data["model_id"])->first();
                    $name = $getName->name ?? "";
                    $hyperlink = "<a href='" . Deeplink::getShortLink('product', $data["model_id"]) . "'>" . $name . "</a>";
                    $type = "Product Review";
                }
                $pay["subwalletGuid"] = config("payment.PAYTM_GUID_TASTING");
                $pay["comments"] = $data["comment"] ?? "Remuneration for reviewing a product on TagTaste.";
            } else if ($data["model_type"] == "Survey") {
                $getName = Surveys::where("id", $data["model_id"])->first();
                $name = $getName->title ?? "";
                $hyperlink = "<a href='" . Deeplink::getShortLink('surveys', $data["model_id"]) . "'>" . $name . "</a>";
                $pay["subwalletGuid"] = config("payment.PAYTM_GUID_SURVEY");
                $pay["comments"] = $data["comment"] ?? "Remuneration for taking a survey on TagTaste.";
                $type = "Survey";
            } else {
                $hyperlink = '';
                $pay["subwalletGuid"] = config("payment.PAYTM_GUID_TASTING");
                $pay["comments"] = $data["comment"] ?? "Payment from Tagtaste.";
            }

            $current_time = Carbon::now()->toDateTimeString();
            $channel = 'App\\Services\\' . $paymentChannel;
            if (!method_exists($channel, 'createLink')) {
                throw new Exception("Payment Channel Missing");
                return false;
            }
            $response = $channel::createLink($pay);

            file_put_contents(storage_path("logs/") . $paymentChannel . "_logs.txt", "\n-----------------CREATE LINK-------------------\n\n\n", FILE_APPEND);
            file_put_contents(storage_path("logs/") . $paymentChannel . "_logs.txt", json_encode($response), FILE_APPEND);
            file_put_contents(storage_path("logs/") . $paymentChannel . "_logs.txt", "\n-----------------SAVING DATA -------------------\n\n\n", FILE_APPEND);

            if (!empty($response)) {
                $resp = $response;
                if (!is_array($response)) {
                    $resp = json_decode($response, true);
                }

                if ($resp["status"] == "SUCCESS") {
                    $dataToUpdate = ["expired_at" => date("Y-m-d H:i:s", strtotime($resp["result"]["expiryDate"])), "payout_link_id" => $resp["result"]["payoutLinkId"], "status_json" => json_encode($resp), "status_id" => config("constant.PAYMENT_PENDING_STATUS_ID")];
                    if (isset($resp["result"]["comments"])) {
                        $dataToUpdate["comments"] = $resp["result"]["comments"];
                    }
                    if (isset($resp["result"]["payoutLink"])) {
                        $dataToUpdate["link"] = $resp["result"]["payoutLink"];
                    }

                    event(new PaymentTransactionCreate($data["model"], null, ["title" => "Payment Link Generated", "name" => $data["name"], "order_id" => $data["transaction_id"], "amount" => $pay["amount"], "pretext" => $hyperlink, "type" => $type,"payout_amount" => $data["payout_amount"],"tds_amount" => $data["tds_amount"],"email"=>$data["email"]]));
                    
                    if ($data["tds_amount"] > 0){
                        $profile_link = env('APP_URL').'/profile/'.$data["model"]["profile_id"];
                        $txn_link = env('SKYNET_URL').'/main/payment-management/passbook';
    
                        \Mail::to("sahil@tagtaste.com")->send(new TdsDeductionFinanceMail(["title" => "Payment Link Generated", "name" => $data["name"], "order_id" => $data["transaction_id"], "amount" => $pay["amount"], "pretext" => $hyperlink, "type" => $type,"payout_amount" => $data["payout_amount"],"tds_amount" => $data["tds_amount"], "created_at"=>$current_time,"email"=>$data["email"],"profile_link"=> $profile_link, "txn_link"=> $txn_link]));      
                    }

                    return PaymentLinks::where("transaction_id", $data["transaction_id"])->update($dataToUpdate); //
                } else {    
                    PaymentLinks::where("transaction_id", $data["transaction_id"])->update(["status_json" => json_encode($resp)]); //
                    return false;
                }
            }
        }
    }

    public function getStatus($transaction_id)
    {

        $getChannel = PaymentLinks::where("transaction_id", $transaction_id)->first();
        if (empty($getChannel)) {
            throw new Exception("Transaction ID Doesnt Exists - getStatus");
            return false;
        }
        $paymentChannel = (empty($getChannel->payment_channel) ? 'Paytm' : $getChannel->payment_channel);
        $channel = 'App\\Services\\' . $paymentChannel;
        if (!method_exists($channel, 'getStatus')) {
            throw new Exception("Payment Channel Missing");
            return false;
        }

        $response = $channel::getStatus($getChannel);

        file_put_contents(storage_path("logs/") . $paymentChannel . "_logs.txt", "\n-----------------GET STATUSK-------------------\n\n\n", FILE_APPEND);
        file_put_contents(storage_path("logs/") . $paymentChannel . "_logs.txt", json_encode($response), FILE_APPEND);
        file_put_contents(storage_path("logs/") . $paymentChannel . "_logs.txt", "\n-----------------SAVING DATA -------------------\n\n\n", FILE_APPEND);
        if (!empty($response)) {
            $resp = $response;
            if (!is_array($response)) {
                $resp = json_decode($response, true);
            }
            if (isset($resp["status"])) {
                $data = ["link" => $resp["result"]["payoutLink"], "payout_link_id" => $resp["result"]["payoutLinkId"], "status_json" => json_encode($resp)];
                if (isset($resp["result"]["expiryDate"])) {
                    $data["expired_at"] = $resp["result"]["expiryDate"];
                }
                if (isset($resp["status"]) && $resp["status"] == "SUCCESS") {
                    $data["status_id"] = config("constant.PAYMENT_SUCCESS_STATUS_ID");
                } else if (isset($resp["status"]) && $resp["status"] == "FAILURE") {
                    $data["status_id"] = config("constant.PAYMENT_FAILURE_STATUS_ID");
                } else if (isset($resp["status"]) && $resp["status"] == "CANCELLED") {
                    $data["status_id"] = config("constant.PAYMENT_CANCELLED_STATUS_ID");
                } else if (isset($resp["status"]) && $resp["status"] == "EXPIRED") {
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
        $responseJson = [];

        $responseJson["result"] = $inputs;
        $dataStr = json_encode($inputs);
        file_put_contents(storage_path("logs/") . "callback_logs.txt", $dataStr, FILE_APPEND);
        file_put_contents(storage_path("logs/") . "callback_logs.txt", "\n++++++++++++++++++++++\n", FILE_APPEND);
        if ($request->has("status") && $request->has("result") && !empty($request->result["orderId"])) {
            $txn_id = $request->result["orderId"];
            $responseJson["result"] = $inputs["result"];
        } else if (isset($request->cashgramid)) {
            $txn_id = $request->cashgramid;
        } else if (isset($request->event)) {
            // print_r($request->all());
            $txn_id = $request->payload["payout_link"]["entity"]["receipt"];
            $responseJson = $request->except('payload');
            $responseJson["result"] = $request->payload["payout_link"]["entity"];
        }

        if (isset($txn_id) && !empty($txn_id)) {
            $getChannel = PaymentLinks::where("transaction_id", $txn_id)->first();
            if (empty($getChannel)) {
                throw new Exception("Transaction ID Doesnt Exists - Callback");
                return false;
            }
            $paymentChannel = (empty($getChannel->payment_channel) ? 'Paytm' : $getChannel->payment_channel);
            $channel = 'App\\Services\\' . $paymentChannel;
            if (!method_exists($channel, 'getStatus')) {
                throw new Exception("Payment Channel Missing");
                return false;
            }

            $response = $channel::processCallback($request);
            // $dataStr = json_encode($inputs);
            // file_put_contents(storage_path("logs/") . "paytm_callback_logs.txt", $dataStr, FILE_APPEND);
            // file_put_contents(storage_path("logs/") . "paytm_callback_logs.txt", "\n++++++++++++++++++++++\n", FILE_APPEND);

            if (!empty($getChannel) && isset($response["orderId"]) && isset($response["status"])) {
                $responseJson["statusCode"] = 200;
                $get = $getChannel;
                $content = [];
                $data = [];

                if ($response["status"] == "SUCCESS") {
                    $content = ["descp" => "We're writing to let you know that your payment has been successfully redeemed.", "status" => "SUCCESSFUL", "subject" => "Redemption Successful", "view" => "emails.payment-success"];
                    $responseJson["status"] = "SUCCESS";
                    $data["status_id"] = config("constant.PAYMENT_SUCCESS_STATUS_ID");
                } else if ($response["status"] == "FAILURE") {
                    $responseJson["status"] = "FAILURE";
                    $data["status_id"] = config("constant.PAYMENT_FAILURE_STATUS_ID");
                    $content = ["descp" => "We're writing to let you know that your attempt for payment redemption failed.", "status" => "FAILED", "subject" => "Redemption Failed", "view" => "emails.payment-failure"];
                } else if ($response["status"] == "CANCELLED") {
                    $responseJson["status"] = "CANCELLED";
                    $data["status_id"] = config("constant.PAYMENT_CANCELLED_STATUS_ID");
                    $content = ["descp" => "We're writing to let you know that payment for " . $response["orderId"] . " has been Canceled.", "status" => "CANCELED", "subject" => "Payment Cancelled", "view" => "emails.payment-cancelled"];
                } else if ($response["status"] == "EXPIRED") {
                    $responseJson["status"] = "EXPIRED";
                    $data["status_id"] = config("constant.PAYMENT_EXPIRED_STATUS_ID");
                    $content = ["descp" => "We're writing to let you know that your payment link has expired.", "status" => "LINK EXPIRED", "subject" => "Payment Link Expired", "view" => "emails.payment-expired"];
                }

                $content["amount"] = $get->amount;
                $links = "";
                if ($get->model_type == "Survey") {

                    $getName = Surveys::where("id", $get->model_id)->first();
                    $name = $getName->title ?? "";
                    $links = "<a href='" . Deeplink::getShortLink('surveys', $get->model_id) . "'>" . $name . "</a>";
                    $headline = "TagTaste Survey Payment";
                    $content["type"] = "Survey";
                } else if ($get->model_type == "Public Review") {

                    $getName = PublicReviewProduct::where("id", $get->model_id)->first();
                    $name = $getName->name ?? "";
                    $links = "<a href='" . Deeplink::getShortLink('product', $get->model_id) . "'>" . $name . "</a>";
                    $headline = "TagTaste Product Review Payment";
                    $content["type"] = "Product Review";
                } else if ($get->model_type == "Private Review") {

                    $getName = Collaborate::where("id", $get->model_id)->first();
                    $name = $getName->title ?? "";
                    $links = "<a href='" . Deeplink::getShortLink('collaborate', $get->model_id) . "'>" . $name . "</a>";
                    $headline = "TagTaste Private Review Payment";
                    $content["type"] = "Collaboration";
                }
                $content["order_id"] = $response["orderId"];
                $content["pretext"] = $links;
                $content["headline"] = $headline;
                $data["status_json"] = json_encode($responseJson);
                if (isset($content["subject"])) {
                    event(new PaymentTransactionStatus($get, null, $content));
                }
                // file_put_contents(storage_path("logs/") . "paytm_callback_logs.txt", "\n-----------------SAVING DATA -------------------\n\n\n", FILE_APPEND);
                PaymentLinks::where("transaction_id", $response["orderId"])->update($data);
            }
        }
        return "i have received callback";
    }
}
