<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    //
    
    public function updateSetting($type, Request $request)
    {
        $hash = $request->input('k');
        $decryptedString = Crypt::decryptString($hash);
        if (filter_var($decryptedString, FILTER_VALIDATE_EMAIL)) {
            \DB::table('newsletters')->where('email',$decryptedString)->update(['is_unsubscribed'=>1]);
            return redirect(env('APP_URL')."/unsubscribed/?k=".$hash);
        }
        $info = explode("/",$decryptedString);
        for ($i=0; $i <5 ; $i++) { 
            if($info[$i]==="0")
            $info[$i]=NULL;
        }
            $preference = Setting::getNotificationPreference($info[0], $info[1], $info[2],$info[3],$info[4]);
        $setting = Setting::getSetting($preference->setting_id,$info[0],$info[1]);
        if($type === 'unsubscribe')
        {
            $reason_id = $request->input('reason_id');
            if(is_null($reason_id) || !isset($reason_id))
            {
                return $this->sendError("Reason should be selected");
            }
            $model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reason_id, 'profile_id'=>$info[0], 'company_id'=>$info[1], 'action'=>$info[2], 'model'=>$info[3]]);
            $setting->{'email_value'} = 0;
            $setting->save();   
            return redirect(env('APP_URL')."/unsubscribed/?k=".$hash); 
        }
        if($type === 'subscribe')
        {
            $setting->{'email_value'} = 1;
            $setting->save();
            return redirect(env('APP_URL')."/subscribed");
        }
        else
        {
            return $this->sendError("undefined type");
        }
        
        
    }


}
