<?php

namespace App\Http\Controllers\Api;

use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $profile_id = \request()->user()->profile->id;

        $models = Setting::getAllSettings($profile_id);
        $this->model = $this->formatData($models);

        return $this->sendResponse();
    }

    public function profileSettings($type)
    {
        $profile_id = \request()->user()->profile->id;

        $query = \DB::raw('SELECT s.id, s.title, s.'.$type.'_description, COALESCE(n.'.$type.'_active, s.'.$type.'_active) AS '.$type.'_active,
                             COALESCE(n.'.$type.'_value, s.'.$type.'_value) AS '.$type.'_value, s.group_name
                            FROM settings s LEFT JOIN notification_settings n ON s.id = n.setting_id AND n.profile_id = '.$profile_id.'
                            WHERE s.'.$type.'_visibility = 1 AND s.belongs_to = \'profile\';');
        $models = \DB::select($query);

        $data = [];

        foreach ($models as $m) {
            $data[$m->group_name][] = [
                'id' => $m->id,
                'title' => $m->title,
                'description' => $m->{$type.'_description'},
                'active' => $m->{$type.'_active'} ? true : false,
                'value' => $m->{$type.'_value'} ? true : false,
            ];
        }

        $this->model = $data;

        return $this->sendResponse();


    }

    public function showCompany($id)
    {
        $profile_id = \request()->user()->profile->id;

        $models = Setting::getAllSettings($profile_id, $id);

        $this->model = $this->formatData($models);

        return $this->sendResponse();
    }

    public function companySettings($type, $id)
    {
        $profile_id = \request()->user()->profile->id;

        $query = \DB::raw('SELECT s.id, s.title, s.'.$type.'_description, COALESCE(n.'.$type.'_active, s.'.$type.'_active) AS '.$type.'_active,
                             COALESCE(n.'.$type.'_value, s.'.$type.'_value) AS '.$type.'_value, s.group_name
                            FROM settings s LEFT JOIN notification_settings n ON s.id = n.setting_id AND n.profile_id = '.$profile_id.' AND n.company_id = '.$id.'
                            WHERE s.'.$type.'_visibility = 1 AND s.belongs_to = \'company\';');
        $models = \DB::select($query);

        $data = [];

        foreach ($models as $m) {
            $data[$m->group_name][] = [
                'id' => $m->id,
                'title' => $m->title,
                'description' => $m->{$type.'_description'},
                'active' => $m->{$type.'_active'} ? true : false,
                'value' => $m->{$type.'_value'} ? true : false,
            ];
        }

        $this->model = $data;

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
        $profile_id = \request()->user()->profile->id;

        $company_id = isset($input['company_id']) ? $input['company_id'] : null;

        $setting = Setting::getSetting($input['setting_id'],$profile_id,$company_id);
        if(is_null($setting)) {
            $this->addError('Setting does not exists');
            return $this->sendResponse();
        }
        $setting->{$input['type'].'_value'} = !!$input['value'];
        $setting->save();

        $models = Setting::getAllSettings($profile_id);

        $this->model = $this->formatData($models);

        return $this->sendResponse();
    }

    public function test($id) {

        $profile_id = \request()->user()->profile->id;

//        $res = Setting::getSetting($id, $profile_id, 21);
        $res = Setting::getNotificationPreference($profile_id, null, 'apply',null,'collaborate');

//        $res->email_value = !$res->email_value;
//        $res->save();

        return response()->json($res);
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

        $data2 = [];
        foreach ($types as $type) {
            $groups = [];
            foreach ($data[$type] as $key => $items) {
                $groups[] = ['group_name' => $key, 'items' => $items];
            }
            $data2[$type] = $groups;
        }

        return $data2;

    }
}
