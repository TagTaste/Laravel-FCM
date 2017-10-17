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
        return static::select('key','value',\DB::raw('count(`key`) as count'))
            ->groupBy('key','value')->orderBy('count','desc')->take(10)->get()->groupBy('key');
    }

}