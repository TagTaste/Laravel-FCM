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
            $url = config("app.gupshup.URL") . config("app.gupshup.APP_ID");

            $client = curl_init($url);
            $data = ["destination" => str_replace("+", "", $mobile), "message" => $message];
            
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($client, CURLINFO_HEADER_OUT, true);
            curl_setopt($client, CURLOPT_POST, true);
            curl_setopt($client, CURLOPT_POSTFIELDS, $data);

            // Set HTTP Header for POST request 
            curl_setopt(
                $client,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: ' . config("app.gupshup.API_KEY"),
                )
            );

            // Submit the POST request
            $resp = curl_exec($client);
            curl_close($client);
            
            if (!is_array($resp)) {
                $resp = json_decode($resp, true);
            }

            // if (isset($resp["status"]) && $resp["status"] == "submitted") {
                return true;
            // }
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
