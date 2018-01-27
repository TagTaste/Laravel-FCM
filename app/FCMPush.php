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

        // For Android
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


        // For iOS
        unset($iosData['model']['content']);        // due to 4kb limit of notification payload in iOS
        $iosDataBuilder = new PayloadDataBuilder();
        $iosDataBuilder->addData(['data' => $iosData]);
        $data = $iosDataBuilder->build();

        $notificationBody = isset($iosData['profile']['name']) ? $iosData['profile']['name'].' '.$this->message($iosData['action'], $iosData['model']['name']) : $this->message('null');
        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setBody($notificationBody)->setSound('default');
//        $message = $data['profile']['name'].$this->message($data['action']);
        $notification = $notificationBuilder->build();

        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','ios')->get()->pluck('fcm_token')->toArray();
        if(count($token))
        {
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
        }

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
