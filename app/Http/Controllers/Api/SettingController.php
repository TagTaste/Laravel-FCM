<?php namespace App\Http\Controllers\Api;

use App\CompanyUser;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function showProfile()
    {
        $profile_id = request()->user()->profile->id;

        $models = Setting::getAllSettings($profile_id);
        $this->model = $this->formatData($models);

        return $this->sendResponse();
    }

    public function showCompany($id)
    {
        $profile_id = request()->user()->profile->id;

        $checkAdmin = CompanyUser::checkAdmin($profile_id, $id);
        if(!$checkAdmin) {
            return response()->json(['data' => null, 'errors' => ["User does not belong to this company."], 'messages' => null],401);
        }

        $models = Setting::getAllSettings($profile_id, $id);

        $this->model = $this->formatData($models);

        return $this->sendResponse();
    }


    public function store(Request $request)
    {
        /// request schema
        /// company_id = int (nullable)
        /// setting_id = int (required)
        /// type = {bell, push, email} (required)
        /// value = {true, false} (required)

        $input = $request->all();
        $profile_id = request()->user()->profile->id;

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

        return $this->sendResponse();
    }


    private function formatData($models) : array {

        // TODO: simplify this function.

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
}
