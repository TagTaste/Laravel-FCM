<?php

namespace App;

use App\Chat\Message;
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
        if($data["action"] === 'upgrade-apk')
        {
            $this->upgradeApk($data,$notifiable->id);
        }
        else
        {
            $this->fcmNotification($data,$notifiable->id);
        }
    }
    
    public function fcmNotification($data,$profileId)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $iosData = $data;
        // For Android
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['data' => $data]);
        \Log::info($dataBuilder);
        $option = $optionBuilder->build();
        $data = $dataBuilder->build();
        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','android')->get()->pluck('fcm_token')->toArray();
        if(count($token))
        {
            if($iosData['action'] == 'chat' || $iosData['action'] == 'message')
            {
                $extraData = $iosData;
                $message = Message::where('chat_id',$iosData['model']['id'])->whereNull('read_on')->orderBy('created_at','desc')->take(5)->get();
                $extraData['model']['latestMessages'] = $message;
                // For Android
                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData(['data' => $extraData]);

                $option = $optionBuilder->build();
                $data = $dataBuilder->build();
            }
            $downstreamResponse = FCM::sendTo($token, $option, null, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
        }


        // For iOS
        unset($iosData['model']['content']);        // due to 4kb limit of notification payload in iOS
        $iosDataBuilder = new PayloadDataBuilder();
        $iosDataBuilder->addData(['data' => $iosData]);
        $data = $iosDataBuilder->build();
        $notificationCount = \DB::table('notifications')->whereNull('read_at')->where('notifiable_id',$profileId)->count();
        $notificationBody = isset($iosData['profile']['name']) ? $this->message($iosData['action'],$iosData['notification']) : $this->message('null');
        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setBody($notificationBody)->setSound('default')->setBadge($notificationCount);
    //        $message = $data['profile']['name'].$this->message($data['action']);
        $notification = $notificationBuilder->build();

        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','ios')->get()->pluck('fcm_token')->toArray();
        if(count($token) && !\Redis::sIsMember("online:profile:",$profileId))
        {   
            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
        }

    }

    protected function message($type,$notifications = null)
    {
        if($notifications == null)
            return "Notification from TagTaste";
        return $notifications;
    }

    protected function upgradeApk($data,$profileId)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

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
    }


}
