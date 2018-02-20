<?php

namespace App\Filter;

use App\Filter;

class Job extends Filter {
    private static $maxFilters = 6;

    protected $table = "job_filters";

    protected $strings = ['location','expected_role','Joining'=>'joining','experience_min','experience_max','compensation_min'=>'salary_min'
        ,'compensation_max'=>'salary_max'];
    
    protected $models = ['By Company'=>'company.name','Job Type' => 'jobType.name',
        'By Person'=> 'profile.name'];
    
    public static $cacheKey = "job:";
    
    public static $relatedColumn = 'job_id';

    public static $filterOrder = ['Job Type','location','experience_min','experience_max','compensation_min','compensation_max'
        ,'By Company','By Person','Joining In'];

    public function getprofile_nameattribute($model)
    {
        if(!$model->company_id){
            return $model->profile->name;
        }
    }

    public static function getFilters($model = null)
    {
        $filterClass = static::class;
        if($model){
            $filterClass = "\\App\\Filter\\" . ucfirst($model);
        }
        $allFilters = $filterClass::select('key','value',\DB::raw('count(`key`) as count'))
            ->groupBy('key','value')->orderBy('count','desc')->get()->groupBy('key');
        $filters = [];
        //$allFilters = $allFilters->keyBy('key');
        $order = $filterClass::$filterOrder;

        if(count($order))
        {
            foreach($order as $key){
                $count = 0;
                $singleFilter = $allFilters->get($key);
                if(!$singleFilter)
                {
                    continue;
                }
                $isSingleKey = true;
                foreach($singleFilter as &$filter)
                {
                    if(!$isSingleKey)
                    {
                        break;
                    }
                    if($key == 'experience_max' && $isSingleKey)
                    {
                        $isSingleKey = false;
                        $filter = $singleFilter->where('value', $singleFilter->max('value'))->first();
                    }
                    else if($key == 'experience_min' && $isSingleKey)
                    {
                        $isSingleKey = false;
                        $filter = $singleFilter->where('value', $singleFilter->min('value'))->first();
                    }
                    else if($key == 'compensation_max' && $isSingleKey)
                    {
                        $isSingleKey = false;
                        $filter = $singleFilter->where('value', $singleFilter->max('value'))->first();
                    }
                    else if($key == 'compensation_min' && $isSingleKey)
                    {
                        $isSingleKey = false;
                        $filter = $singleFilter->where('value', $singleFilter->min('value'))->first();
                    }
                    if(!array_key_exists($key,$filters)){
                        $filters[$key] = [];
                    }

                    $filters[$key][] = ['value' => $filter->value,'count'=>$filter->count];
                    $count++;
                    if($count >= static::$maxFilters){
                        break;
                    }
                }
            }

        }
        else
        {
            foreach($allFilters as $key=>&$sub){
                $count = 0;
                foreach($sub as &$filter){
                    $filters[$key][] = ['value' => $filter->value,'count'=>$filter->count];
                    $count++;
                    if($count >= static::$maxFilters){
                        break;
                    }
                }
            }
        }

        return $filters;
    }

}