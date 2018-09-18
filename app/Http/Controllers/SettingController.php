<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    //
    
    public function updateSetting(Request $request, $type)
    {
        $hash = $request->input('k');
        $decryptedString = Crypt::decryptString($hash);
    
        $info = explode("/",$decryptedString);
        $emailValue = 1;
        if($type == 'unsubscribe')
            $emailValue = 0;
        if(isset($info[2]) && !is_null($info[2]) && !empty($info[2]))
        {
            $setting = Setting::getSetting($info[0],$info[1],$info[2]);
            $this->model = \DB::table('notification_settings')->where('setting_id',$info[0])->where('profile_id',$info[1])
                ->where('company_id',$info[2])->update(['email_value'=>$emailValue]);
        }
        else
        {
            $setting = Setting::getSetting($info[0],$info[1],null);
            $this->model = \DB::table('notification_settings')->where('setting_id',$info[0])->where('profile_id',$info[1])
                ->update(['email_value'=>$emailValue]);
        }
        if(!$this->model)
        {
            if(is_null($setting)) {
                $this->addError('Setting does not exists');
                return $this->sendResponse();
            }
            $setting->{'email_value'} = $emailValue;
            $setting->save();
            $this->model = 1;
        }
        return response()->json(["data"=>$this->model,"error"=>null,"status"=>200]);

    }

    public function reasonUnsubscribe(Request $request)
    {
        $hash = $request->input('k');
        $decryptedString = Crypt::decryptString($hash);
    
        $info = explode("/",$decryptedString);

        $reasonId = $request->input('reasonId');
        if(is_null($reasonId) || !isset($reasonId) || !isset($info[0]) || !isset($info[1]) || !isset($info[2]))
        {
            return $this->sendError("Reason should be selected");
        }
        if(isset($info[2]) && !is_null($info[2]) && !empty($info[2]))
        {

        $this->model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reasonId, 'profile_id'=>$info[1], 'company_id'=>$info[2], 'setting_id'=>$info[0]]);
        }
        else
        {
            $this->model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reasonId, 'profile_id'=>$info[1], 'setting_id'=>$info[0]]);
        }
        return response()->json(["data"=>$this->model,"error"=>null,"status"=>200]);
    }

    public function getUnsubscribeReason()
    {
        $this->model = \DB::table('unsubscribe_reasons')->get();
        return response()->json(["data"=>$this->model,"error"=>null,"status"=>200]);
    }


}
