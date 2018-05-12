<?php

namespace App;


class SuggestionEngine
{
    private $suggestion = ['profile','company','collaborate','job'];

    public $loggedInProfileData;

    public function __construct()
    {
        $this->loggedInProfileData = \DB::table('profile_filters')->where('profile_id',request()->user()->profile->id)->get();
    }

    public function noviceProfile()
    {
        $rand = rand(1,2);
        return $rand == 1 ? $this->profileFilterData() : $this->companyFilterData();
    }

    public function profileFilterData()
    {
        $data = \DB::table('profile_filters')->select('profile_id')->where('profile_id','!=',request()->user()->profile->id);
        $model = [];
        foreach ($this->loggedInProfileData as $datum)
        {
            $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('profile_id');
            foreach ($x as $y)
                $model[] = $y;
        }
        return $this->loggedInProfileData;
    }

    public function companyFilterData()
    {
        $data = \DB::table('company_filters')->select('profile_id')->where('profile_id','!=',request()->user()->profile->id);
        $model = [];
        foreach ($this->loggedInProfileData as $datum)
        {
            if($datum->key == 'skills')
                $datum->key = 'speciality';
            $x = $data->where('value','like','%'.$datum->value.'%')->where('key',$datum->key)->get()->pluck('company_id');
            foreach ($x as $y)
                $model[] = $y;
        }
        return $this->loggedInProfileData;
    }
}
