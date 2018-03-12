<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show()
    {
        $profile_id = \request()->user()->profile->id;

        $query = \DB::raw('SELECT s.id, s.title, s.email_description, s.push_description, s.bell_description,  
                            s.email_visibility, s.push_visibility, s.bell_visibility,  
                            COALESCE(n.email_active, s.email_active) AS email_active,
                            COALESCE(n.push_active, s.push_active) AS push_active,
                            COALESCE(n.bell_active, s.bell_active) AS bell_active,
                            COALESCE(n.email_value, s.email_value) AS email_value, 
                            COALESCE(n.push_value, s.push_value) AS push_value, 
                            COALESCE(n.bell_value, s.bell_value) AS bell_value, 
                            s.group_name
                            FROM settings s LEFT JOIN notification_settings n ON s.id = n.setting_id AND n.profile_id = '.$profile_id.'
                            WHERE s.belongs_to = \'profile\';');
        $models = \DB::select($query);

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

        $this->model = $data;

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

        $query = \DB::raw('SELECT s.id, s.title, s.email_description, s.push_description, s.bell_description,  
                            s.email_visibility, s.push_visibility, s.bell_visibility,  
                            COALESCE(n.email_active, s.email_active) AS email_active,
                            COALESCE(n.push_active, s.push_active) AS push_active,
                            COALESCE(n.bell_active, s.bell_active) AS bell_active,
                            COALESCE(n.email_value, s.email_value) AS email_value, 
                            COALESCE(n.push_value, s.push_value) AS push_value, 
                            COALESCE(n.bell_value, s.bell_value) AS bell_value, 
                            s.group_name
                            FROM settings s LEFT JOIN notification_settings n ON s.id = n.setting_id AND n.profile_id = '.$profile_id.' AND n.company_id = '.$id.'
                            WHERE s.belongs_to = \'company\';');
        $models = \DB::select($query);

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

        $this->model = $data;

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

        $settingExists = \DB::table('notification_settings')->where('setting_id', $input['setting_id'])
            ->where('profile_id', $profile_id)
            ->where('company_id', $company_id)
            ->exists();

        if($settingExists) {
            \DB::table('notification_settings')->where('setting_id', $input['setting_id'])
                ->where('profile_id', $profile_id)
                ->where('company_id', $company_id)
                ->update([ $input['type'].'_value' => $input['value'] ]);
        } else {
            $setting = \DB::table('settings')->where('id', $input['setting_id'])->first();
            $setting->{$input['type'].'_value'} = $input['value'];
            \DB::table('notification_settings')->insert([
                'setting_id' => $input['setting_id'],
                'profile_id' => $profile_id,
                'company_id' => $company_id,
                'bell_visibility' => $setting->bell_visibility,
                'email_visibility' => $setting->email_visibility,
                'push_visibility' => $setting->push_visibility,
                'bell_active' => $setting->bell_active,
                'email_active' => $setting->email_active,
                'push_active' => $setting->push_active,
                'bell_value' => $setting->bell_value,
                'email_value' => $setting->email_value,
                'push_value' => $setting->push_value,
            ]);
        }
        $this->model = true;
        return $this->sendResponse();
    }
}
