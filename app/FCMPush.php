<?php

namespace App;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class FCMPush extends Model
{
    public function send($notifiable,Notification $notification)
    {
        $data = $notification->toArray($notifiable);
        $this->fcmNotification($data,$notifiable->id);
    }
    
    public function fcmNotification($data,$profileId)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $iosData = $data;

        //android
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['data' => $data]);

        $option = $optionBuilder->build();
        $data = $dataBuilder->build();
        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','android')->get()->pluck('fcm_token')->toArray();
        if(count($token))
        {
            $downstreamResponse = FCM::sendTo($token, $option, null, $data);
            $downstreamResponse->numberSuccess();
        }

        // android


        //for ios
        unset($iosData['model']['content']);
        $iosDataBuilder = new PayloadDataBuilder();
        $iosDataBuilder->addData(['data' => $iosData]);
        $data = $iosDataBuilder->build();

        $notificationBody = isset($iosData['profile']['name']) ? $iosData['profile']['name'].' '.$this->message($iosData['action'], $iosData['model']['name']) : $this->message('null');
        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setBody($notificationBody)->setSound('default');
//        $message = $data['profile']['name'].$this->message($data['action']);

//        $notificationBuilder = new PayloadNotificationBuilder();
        $notification = $notificationBuilder->build();

//        \Log::info(print_r($data, TRUE));
//        \Log::info('it worked! profileId='.print_r($profileId,TRUE));

        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','ios')->get()->pluck('fcm_token')->toArray();
//        \Log::info(print_r($token, TRUE));
        if(count($token))
        {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

            $n = $downstreamResponse->numberSuccess();
//            \Log::info("numberSuccess: ".print_r($n,TRUE));
        }
        //for ios

//        \Log::info("numberFailure: ".print_r($downstreamResponse->numberFailure(),TRUE));
//        \Log::info("numberModification: ".print_r($downstreamResponse->numberModification(),TRUE));

//return Array - you must remove all this tokens in your database
//        \Log::info("tokensToDelete: ".print_r($downstreamResponse->tokensToDelete(),TRUE));

//return Array (key : oldToken, value : new token - you must change the token in your database )
//        \Log::info("tokensToModify: ".print_r($downstreamResponse->tokensToModify(), TRUE));

//return Array - you should try to resend the message to the tokens in the array
//        \Log::info("tokensToRetry: ".print_r($downstreamResponse->tokensToRetry(), TRUE));
    }

    protected function message($type, $modelType = null)
    {
        if($type == "comment"){
            return "commented on your post";
        }
        if($type == "like"){
            return "liked your post";
            }
        if($type == "share"){
            return "shared a post";
        }
        if($type == "tag"){
            return "tagged you in a post";
        }
        if($type == "message"){
            return "sent you a message";
        }
        if($type == "follow"){
            return "has started following you." ;
        }
        if($type == "apply") {
            return "has applied to your $modelType post.";
        }
        return "Notification from TagTaste";
    }
}
