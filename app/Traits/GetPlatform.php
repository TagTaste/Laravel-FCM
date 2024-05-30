<?php


namespace App\Traits;

trait GetPlatform
{
    public function getPlatform($request){
        $versionKey = 'X-VERSION';
        $versionKeyIos = 'X-VERSION-IOS';

        if ($request->hasHeader($versionKey)) {
            return "android";
        } else if ($request->hasHeader($versionKeyIos)){
            return "ios";
        } else {
            return "web";
        }
    }
}