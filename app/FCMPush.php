<?php

namespace App;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use Illuminate\Database\Eloquent\Model;

class FCMPush extends Model
{
    public function fcmNotification($titile,$message = null,$data = null,$token)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($titile);
        $notificationBuilder->setBody($message)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $data]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

//return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

//return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

//return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();
    }
}
