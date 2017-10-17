<?php

namespace App\Filter;

use App\Filter;
use Illuminate\Database\Eloquent\Model;

class Profile extends Filter {

    protected $table = "profile_filters";
    
    private $csv = ['keywords','expertise'];
    
    private $strings = ['location'];
    
    public function getProfileAttribute()
    {
        $key = "profile:small:" . $this->profile_id;
        return json_decode(\Redis::get($key));
    }
    
    public static function addKey($profileId, $key, $value, $delimiter=false)
    {
        if(!$delimiter){
            return static::insert(
                [
                    'profile_id'=>$profileId,
                    'key' => $key,
                    'value' => $value
                ]);
        }
        
        $data = [];
        $value = explode(',',$value);
        foreach($value as $v){
            $data[] = [
                'profile_id'=>$profileId,
                'key' => $key,
                'value' => $v
            ];
        }
        
        return static::insert($data);
    }
    
    public static function removeKey($profileId,$key,$value = null)
    {
        $filter = static::where('profile_id',$profileId)
            ->where('key',$key);
    
        if($value){
            $filter = $filter->where('value',$value);
        }
    
        return $filter->delete();
    }
    
    public static function updateKey($profileId,$key,$value,$separator=false)
    {
       static::removeKey($profileId,$key);
        
        //create new filter
       return static::addKey($profileId,$key,$value,$separator);
        
    }
    
    public static function addModel($model)
    {
        $self = new self;
        foreach($self->csv as $filter){
            if(isset($model->{$filter})){
                static::updateKey($model->id,$filter,$model->{$filter},',');
            }
        }
        
        foreach($self->strings as $filter){
            if(isset($model->{$filter})){
                static::updateKey($model->id,$filter,$model->{$filter});
            }
        }
        
    }
    
    public static function getFilters()
    {
        $filters = static::select('key','value',\DB::raw('count(`key`) as count'))
            ->groupBy('key','value')->orderBy('count','desc')->take(10)->get()->groupBy('key');
        
        foreach($filters as $key=>&$sub){
            foreach($sub as &$filter){
                unset($filter->key);
            }
        }
        return $filters;
    }
    
    public static function getModels($filters)
    {
        $models = null;
        foreach($filters as $filter => $value){

            $profile = static::selectRaw('distinct profile_id')->where('key',$filter)->whereIn('value',$value)->get()->pluck("profile_id");
            if(is_null($models)){
                $models = $profile;
                continue;
            }
            $models = $profile->intersect($models);
        }
        
        if(count($models) == 0){
            return $models;
        }
        
        $profiles = [];
        foreach($models as $model){
            $profiles[] = "profile:small:" . $model;
        }
       
        $profiles = \Redis::mget($profiles);
        
        foreach($profiles as &$model){
            $model = json_decode($model,true);
        }
        
        return $profiles;
    }

}