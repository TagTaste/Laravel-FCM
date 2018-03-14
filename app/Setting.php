<?php

namespace App;


class Setting
{
    protected $table = 'notification_settings';
    protected $timestamps = true;

    public $setting_id, $profile_id, $company_id, $bell_visibility, $email_visibility, $push_visibility,
        $bell_active, $email_active, $push_active, $bell_value, $email_value, $push_value;

    protected $fillable = ['setting_id', 'profile_id', 'company_id', 'bell_visibility', 'email_visibility','push_visibility',
        'bell_active', 'email_active','push_active',
        'bell_value', 'email_value','push_value',
    ];

    public static function getAllSettings($profileId, $companyId = null) {

        $belongsTo = is_null($companyId) ? 'profile' : 'company';
        $companyWhere = is_null($companyId) ? 'n.company_id IS NULL' : "n.company_id = $companyId";

        $query = \DB::raw('SELECT s.id, s.title, s.email_description, s.push_description, s.bell_description,  
                            s.email_visibility, s.push_visibility, s.bell_visibility,  
                            COALESCE(n.email_active, s.email_active) AS email_active,
                            COALESCE(n.push_active, s.push_active) AS push_active,
                            COALESCE(n.bell_active, s.bell_active) AS bell_active,
                            COALESCE(n.email_value, s.email_value) AS email_value, 
                            COALESCE(n.push_value, s.push_value) AS push_value, 
                            COALESCE(n.bell_value, s.bell_value) AS bell_value, 
                            s.group_name
                            FROM settings s LEFT JOIN notification_settings n ON s.id = n.setting_id AND n.profile_id = '.$profileId.' AND '.$companyWhere.'
                            WHERE s.belongs_to = \''.$belongsTo.'\';');
        return \DB::select($query);
    }

    public static function getNotificationPreference($profileId, $companyId = null, $action, $sub_action = null, $model = null) {

        $belongs_to = is_null($companyId) ? 'profile' : 'company';
        $setting = \DB::table('settings_action')->where('belongs_to', $belongs_to)
            ->where('action', $action)
            ->where('sub_action', $sub_action)->where('model', $model)
            ->first();
        if(is_null($setting)) {
            return null;
        }
        return static::getSetting($setting->setting_id, $profileId, $companyId);

    }

    public static function getSetting($settingId, $profileId, $companyId = null) {

        $belongsTo = is_null($companyId) ? 'profile' : 'company';
        $companyWhere = is_null($companyId) ? 'n.company_id IS NULL' : "n.company_id = $companyId";

        $query = \DB::raw('SELECT s.id, s.title, s.email_description, s.push_description, s.bell_description,  
                            s.email_visibility, s.push_visibility, s.bell_visibility,  
                            COALESCE(n.email_active, s.email_active) AS email_active,
                            COALESCE(n.push_active, s.push_active) AS push_active,
                            COALESCE(n.bell_active, s.bell_active) AS bell_active,
                            COALESCE(n.email_value, s.email_value) AS email_value, 
                            COALESCE(n.push_value, s.push_value) AS push_value, 
                            COALESCE(n.bell_value, s.bell_value) AS bell_value, 
                            s.group_name
                            FROM settings s LEFT JOIN notification_settings n ON s.id = n.setting_id AND n.profile_id = '.$profileId.' AND '.$companyWhere.'
                            WHERE s.belongs_to = \''.$belongsTo.'\' AND s.id = '.$settingId.';');
        $model = \DB::select($query);
        $model = $model[0];
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
