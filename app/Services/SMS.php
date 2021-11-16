<?php

namespace App\Services;

use Exception;
use Twilio\Rest\Client as TwilioClient;

class SMS
{

    public static function sendSMS($mobile, $content, $carrier = "gupshup")
    {
        //IntegrationGupShup
        $message = substr($content, 0, 159);
        if ($carrier == "gupshup") {
            $url = config("app.gupshup.URL");


            $data = json_encode(["destination" => str_replace("+", "", $mobile), "message" => $message]);

            //////////////

            $curl = curl_init();
            $post_fields = array();
            $post_fields["method"] = "sendMessage";
            $post_fields["send_to"] = trim(str_replace("+", "", $mobile));
            $post_fields["msg"] = trim($message);
            $post_fields["msg_type"] = "TEXT";
            $post_fields["v"] = "1.1";
            $post_fields["userid"] = config("app.gupshup.UID");

            $post_fields["password"] = config("app.gupshup.PASSWORD");
            // $post_fields["auth_scheme"] = "PLAIN";
            $post_fields["format"] = "JSON";
            // $post_fields["dltTemplateId "] = 1207163531146549448;
            // print_r($post_fields);
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_fields
            ));
            $resp = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {

                throw new Exception("SMS Failed" . json_encode($post_fields));
                return false;
            }

            if (!is_array($resp)) {
                $resp = json_decode($resp, true);
            }

            if (isset($resp["response"]["status"]) && $resp["response"]["status"] == "success") {
                return true;
            }
        } else if ($carrier == "twilio") {
            $accountSid = config('app.twilio.TWILIO_ACCOUNT_SID');
            $authToken  = config('app.twilio.TWILIO_AUTH_TOKEN');

            $client = new TwilioClient($accountSid, $authToken);
            try {
                return $client->messages->create($mobile, ['from' => env('TWILIO_PHONE'), 'body' => $message]);
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
