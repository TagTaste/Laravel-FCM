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
        $emailValue = $type == 'unsubscribe' ? 0 : 1;

        if(isset($info[2]) && !is_null($info[2]))
        {
            $this->model = \DB::table('notification_settings')->where('setting_id',$info[0])->where('profile_id',$info[1])
                ->where('company_id',$info[2])->update(['email_value'=>$emailValue]);
        }
        else
        {
            $this->model = \DB::table('notification_settings')->where('setting_id',$info[0])->where('profile_id',$info[1])
                ->update(['email_value'=>$emailValue]);
        }
        \Log::info("here");
        \Log::info($this->model);
        return response()->json(["data"=>$this->model,"error"=>"","status"=>200]);

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
        $this->model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reasonId, 'profile_id'=>$info[1], 'company_id'=>$info[2], 'setting_id'=>$info[0]]);
        return response()->json(["data"=>$this->model,"error"=>"","status"=>200]);
    }

    public function getUnSubscribeReason()
    {
        $this->model = \DB::table('unsubscribe_reasons')->get();
        return response()->json(["data"=>$this->model,"error"=>"","status"=>200]);
    }


}
