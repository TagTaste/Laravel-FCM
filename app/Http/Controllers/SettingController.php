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
    
        $info = explode("/",$decryptedString);
        for ($i=0; $i <5 ; $i++) { 
            if($info[$i]==="0")
            $info[$i]=NULL;
        }
            $preference = Setting::getNotificationPreference($info[0], $info[1], $info[2],$info[3],$info[4]);
        $setting = Setting::getSetting($preference->setting_id,$info[0],$info[1]);
        if($type === 'unsubscribe')
        {
            $model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reason_id, 'profile_id'=>$info[0], 'company_id'=>$info[1], 'action'=>$info[2], 'model'=>$info[3]]);
            $setting->{'email_value'} = 0;
            $data = $setting->save();  
            return response()->json(["data"=>$data,"error"=>"","status"=>200]); 
        }
        if($type === 'subscribe')
        {
            $setting->{'email_value'} = 1;
            $data = $setting->save();
            return response()->json(["data"=>$data,"error"=>"","status"=>200]); 
        }
        else
        {
            return $this->sendError("undefined type");
        }
        
        
    }

    public function reasonUnsubscribe(Request $request)
    {
        $hash = $request->input('k');
        $decryptedString = Crypt::decryptString($hash);
    
        $info = explode("/",$decryptedString);
        for ($i=0; $i <5 ; $i++) { 
            if($info[$i]==="0")
            $info[$i]=NULL;
        }

        $reasonId = $request->input('reasonId');
            if(is_null($reasonId) || !isset($reasonId))
            {
                return $this->sendError("Reason should be selected");
            }
            $this->model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reasonId, 'profile_id'=>$info[0], 'company_id'=>$info[1], 'action'=>$info[2], 'model'=>$info[3]]);
            return response()->json(["data"=>$this->model,"error"=>"","status"=>200]); 
    }


}
