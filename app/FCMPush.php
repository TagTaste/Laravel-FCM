<?php

namespace App;

use App\Chat\Message;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Redis;

class FCMPush extends Model
{
    public function send($notifiable,Notification $notification)
    {
        file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n Here in FCMPush to send notification.\n", FILE_APPEND); 
        $data = $notification->toArray($notifiable);
        if(isset($data["action"]) && $data["action"] === 'upgrade-apk')
        {
            $this->upgradeApk($data,$notifiable->id);
        }
        else
        {
            file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n Call fcmNotification fucntion.\n", FILE_APPEND); 
            $this->fcmNotification($data,$notifiable->id);
        }
    }
    
    public function fcmNotification($data,$profileId)
    {
        // file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n Here in fcmNotification fucntion.\n", FILE_APPEND); 
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        
//         $jsonData = json_decode(json_encode(($data), true));
//         file_put_contents(storage_path("logs") . "/data_notification_test.txt", print_r($jsonData, true)."\n++++++++++++++++++++++++++++++++++++++\n", FILE_APPEND);
        $data = $this->minimizePushData($data);
//         $jsonData = json_decode(json_encode(($data), true));
//         file_put_contents(storage_path("logs") . "/data_notification_test.txt", print_r($jsonData, true)."\n++++++++++++++++++++++++++++++++++++++\n", FILE_APPEND);
        
        $iosData = $data;
        // For Android
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['data' => $data]);
        $option = $optionBuilder->build();
        $data = $dataBuilder->build();
        $token = \DB::table('app_info')->where('profile_id',$profileId)->where('platform','android')->get()->pluck('fcm_token')->toArray();

//         file_put_contents(storage_path("logs") . "/notification_test.txt", "\nTrying to push for android for profile_id : ".$profileId."and token count:".count($token), FILE_APPEND);
        if(count($token))
        {
            //No need to send latest messages

            // if(isset($iosData['action']) && ($iosData['action'] == 'chat' || $iosData['action'] == 'message'))
            // {
            //     $extraData = $iosData;
            //     $message = Message::where('chat_id',$iosData['model']['id'])->whereNull('read_on')->orderBy('created_at','desc')->take(5)->get();
            //     $extraData['model']['latestMessages'] = $message;
            //     // For Android
            //     $dataBuilder = new PayloadDataBuilder();
            //     $dataBuilder->addData(['data' => $extraData]);

            //     $option = $optionBuilder->build();
            //     $data = $dataBuilder->build();
            // }
            // file_put_contents(storage_path("logs") . "/notification_test.txt", "\nSending push android for profile_id : ".$profileId, FILE_APPEND);
            $downstreamResponse = FCM::sendTo($token, $option, null, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
        }else{
            // file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n Here don't have any token. - ANDROID\n", FILE_APPEND); 
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
//         file_put_contents(storage_path("logs") . "/notification_test.txt", "\nTrying to push for ios for profile_id : ".$profileId."and token count:".count($token), FILE_APPEND);

        if(count($token) && !Redis::sIsMember("online:profile:",$profileId))
        {   
            // file_put_contents(storage_path("logs") . "/notification_test.txt", "\nSending push ios for profile_id : ".$profileId, FILE_APPEND);

            $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
            $downstreamResponse->numberSuccess();
            $downstreamResponse->numberFailure();
        }else{
            // file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n User is online in redis\n", FILE_APPEND); 
        }
//         file_put_contents(storage_path("logs") . "/notification_test.txt", "\n\n++++++++++++++++++++++++++\n\n ", FILE_APPEND);



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

    protected function minimizePushData($data){
        $minData = null;
        if(isset($data['profile'])){
            $profile = $data['profile'];
            $tempProfile = ['id' => $profile['id'], 'name' => $profile['name'], 'type' => $profile['type']];
            $data['profile'] = $tempProfile;
        }

        if(isset($data['actionModel'])){
            $data['actionModel'] = null;
        }

        if(isset($data['model'])){
            $model = $data['model'];
            $tempModel = ['id'=>$model['id'], 'name'=>$model['name']];
            if(isset($model['collaborate_type'])){
                $tempModel['collaborate_type'] = $model['collaborate_type'];
            }     
            // $data['model'] = $tempModel;
        }
        return $data;
    }

}
