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
//        $notificationBuilder = new PayloadNotificationBuilder('my title');
//        $notificationBuilder->setBody('Hello world')
//            ->setSound('default');

//        $message = $data['profile']['name'].$this->message($data['action']);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notification = $notificationBuilder->build();

        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','ios')->get()->pluck('fcm_token')->toArray();
        if(count($token))
        {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, null);
            $downstreamResponse->numberSuccess();
        }
        //for ios

//        $downstreamResponse->numberFailure();
//        $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
//        $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
//        $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
//        $downstreamResponse->tokensToRetry();
    }

    protected function message($type)
    {
        if($type == "comment"){
            return " commented on a post";
        }
        if($type == "like"){
            return " liked a post";
            }
        if($type == "share"){
            return " shared a post";
        }
        if($type == "tag"){
            return " tagged you in a post";
        }
        if($type == "message"){
            return " sent you a message";
        }
        if($type == "follow"){
            return " has started following you." ;
        }
    }
}
