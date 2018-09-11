<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    //
    public function getSettings($profileId, Request $request)
    {
        $hash = $request->input('k');
    	$profile_id = $profileId;
    	$profile = \App\Profile::where('id',$profile_id)->with('user')->first();
    	$email = $profile['user']['email'];
    	if($hashKey === Hash::make($profile_id.$email))
    	{
    		$models = Setting::getAllSettings($profile_id);
        	$this->model = $this->formatData($models);	
    	}
        return $this->model;
    }
    private function formatData($models) : array {

        $data = [];
        $types = ['email', 'bell', 'push'];

        foreach ($models as $m) {
            foreach ($types as $type) {
                if($m->{$type.'_visibility'} == 0) continue;
                $data[$type][$m->group_name][] = [
                    'id' => $m->id,
                    'title' => $m->title,
                    'description' => $m->{$type.'_description'},
                    'active' => $m->{$type.'_active'} ? true : false,
                    'value' => $m->{$type.'_value'} ? true : false,
                ];

            }
        }

        $settingModels = [];
        foreach ($types as $type) {
            $groups = [];
            foreach ($data[$type] as $key => $items) {
                $groups[] = ['group_name' => $key, 'items' => $items];
            }
            $settingModels[$type] = $groups;
        }

        return $settingModels;

    }

    public function store(Request $request)
    {
        /// request schema
        /// company_id = int (nullable)
        /// setting_id = int (required)
        /// type = {bell, push, email} (required)
        /// value = {true, false} (required)

        $input = $request->all();
        $profile_id = $request->input('profile_id');
        $company_id = null;

        if(isset($input['company_id'])) {
            $company_id = $input['company_id'];
            $checkAdmin = CompanyUser::checkAdmin($profile_id, $company_id);
            if(!$checkAdmin) {
                return response()->json(['data' => null, 'errors' => ["User does not belong to this company."], 'messages' => null],401);
            }
        }

        $setting = Setting::getSetting($input['setting_id'],$profile_id,$company_id);
        if(is_null($setting)) {
            $this->addError('Setting does not exists');
            return $this->sendResponse();
        }
        $setting->{$input['type'].'_value'} = !!$input['value'];
        $setting->save();

        $this->model = true;

        return 1;
    }
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
            return $this->error("undefined type");
        }
        
        
    }

    public function reasonUnsubscribe(Request $request)
    {
        $hash = $request->input('k');
        $reason_id = $request->input('reason_id');
        $decryptedString = Crypt::decryptString($hash);
        $info = explode("/",$decryptedString);
        if($info[1] != 0)
        {
            $companyId = null; 
        }
        if($info[1] == 0)
        {
            $companyId = null;
        }
        $model = \DB::table('profile_unsubscribe_reasons')->insert(['reason_id'=>$reason_id, 'profile_id'=>$info[0], 'company_id'=>$companyId, 'action'=>$info[2], 'model'=>$info[3]]);
        return 1;        

    }

}
