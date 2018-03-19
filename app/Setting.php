<?php

namespace App;


class Setting
{
    protected $table = 'notification_settings';
    protected $timestamps = true;

    public $setting_id, $profile_id, $company_id, $bell_visibility, $email_visibility, $push_visibility,
        $bell_active, $email_active, $push_active, $bell_value, $email_value, $push_value;

    protected $fillable = ['setting_id', 'profile_id', 'company_id', 'bell_visibility', 'email_visibility',
        'push_visibility', 'bell_active', 'email_active','push_active', 'bell_value', 'email_value','push_value',
    ];

    public static function getAllSettings($profileId, $companyId = null) {

        $belongsTo = is_null($companyId) ? 'profile' : 'company';

        $models = \DB::table('settings')->leftJoin('notification_settings', function ($join) use ($profileId, $companyId) {
                $join->on('notification_settings.setting_id', '=', 'settings.id')
                    ->where('notification_settings.profile_id', $profileId)
                    ->where('notification_settings.company_id', $companyId);
            })
            ->select('settings.id', 'settings.title', 'settings.email_description', 'settings.push_description',
            'settings.bell_description', 'settings.email_visibility', 'settings.push_visibility', 'settings.bell_visibility',
            \DB::raw("COALESCE(notification_settings.email_active, settings.email_active) AS email_active"),
            \DB::raw("COALESCE(notification_settings.push_active, settings.push_active) AS push_active"),
            \DB::raw("COALESCE(notification_settings.bell_active, settings.bell_active) AS bell_active"),
            \DB::raw("COALESCE(notification_settings.email_value, settings.email_value) AS email_value"),
            \DB::raw("COALESCE(notification_settings.push_value, settings.push_value) AS push_value"),
            \DB::raw("COALESCE(notification_settings.bell_value, settings.bell_value) AS bell_value"), 'settings.group_name')
            ->where('settings.belongs_to', $belongsTo)->get();

        return $models;
    }

    public static function getNotificationPreference($profileId, $companyId = null, $action, $sub_action = null, $model = null) {

        $belongs_to = is_null($companyId) ? 'profile' : 'company';
        $setting = \DB::table('settings_action')->where('belongs_to', $belongs_to)
            ->where('action', $action)
            ->where('sub_action', $sub_action)->where('model', $model)
            ->first();
        \Log::info("SETTING.PHP profile_id=$profileId action=$action setting=".print_r($setting,true));
        if(is_null($setting)) {
            return null;
        }
        return static::getSetting($setting->setting_id, $profileId, $companyId);

    }

    public static function getSetting($settingId, $profileId, $companyId = null) {

        $belongsTo = is_null($companyId) ? 'profile' : 'company';

        $model = \DB::table('settings')->leftJoin('notification_settings', function ($join) use ($profileId, $companyId) {
            $join->on('settings.id', '=', 'notification_settings.setting_id')
                ->where('notification_settings.profile_id', $profileId)
                ->where('notification_settings.company_id', $companyId);
        })
            ->select('settings.id', 'settings.title', 'settings.email_description', 'settings.push_description',
            'settings.bell_description', 'settings.email_visibility', 'settings.push_visibility', 'settings.bell_visibility',
            \DB::raw("COALESCE(notification_settings.email_active, settings.email_active) AS email_active"),
            \DB::raw("COALESCE(notification_settings.push_active, settings.push_active) AS push_active"),
            \DB::raw("COALESCE(notification_settings.bell_active, settings.bell_active) AS bell_active"),
            \DB::raw("COALESCE(notification_settings.email_value, settings.email_value) AS email_value"),
            \DB::raw("COALESCE(notification_settings.push_value, settings.push_value) AS push_value"),
            \DB::raw("COALESCE(notification_settings.bell_value, settings.bell_value) AS bell_value"), 'settings.group_name')
            ->where('settings.belongs_to', $belongsTo)->where('settings.id', $settingId)->first();

        if(is_null($model)) {
            return null;
        }
        $setting = new Setting();
        foreach ($setting->fillable as $var) {
            $setting->{$var} = isset($model->{$var}) ? $model->{$var} : null;
        }
        $setting->setting_id = (int)$settingId;
        $setting->profile_id = (int)$profileId;
        $setting->company_id = $companyId;

        return $setting;

    }

    public function save()
    {
        $settingExists = \DB::table('notification_settings')->where('setting_id', $this->setting_id)
            ->where('profile_id', $this->profile_id)
            ->where('company_id', $this->company_id)
            ->exists();

        if($settingExists) {
            \DB::table('notification_settings')->where('setting_id', $this->setting_id)
                ->where('profile_id', $this->profile_id)
                ->where('company_id', $this->company_id)
                ->update($this->toArray());
        } else {
            \DB::table('notification_settings')->insert($this->toArray());
        }
    }

    public function toArray() {
        $arr = [];
        foreach ($this->fillable as $var) {
            $arr[$var] = $this->{$var};
        }
        return $arr;
    }

}
