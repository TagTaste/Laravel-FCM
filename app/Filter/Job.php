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
                if(!$singleFilter || $key == 'experience_min' || $key == 'experience_max' || $key == 'compensation_min' || $key == 'compensation_max')
                {
                    continue;
                }
                foreach($singleFilter as &$filter)
                {
                    if(!array_key_exists($key,$filters)){
                        $filters[$key] = [];
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
        $filters['experience'][0]['value'] = '0 - 3.5 LPA';
        $filters['experience'][1]['value'] = '3.5 - 7 LPA';
        $filters['experience'][2]['value'] = '7 - 15 LPA';
        $filters['experience'][3]['value'] = '>15 LPA';
        return $filters;
    }


    public static function getModelIds(&$filters,$skip = null,$take = null)
    {
        $models = null;

        foreach($filters as $filter => $value){
            if(array_key_exists('experience_max',$filters) && array_key_exists('experience_min',$filters))
            {
                $model = \DB::table('job_filters as j1')->select('j1.job_id')->JOIN('job_filters as j2','j2.job_id','=', 'j1.job_id')
                    ->where('j2.key','experience_min')->where('j2.value','>=',$filters['experience_min'])
                    ->where('j1.key','experience_max')->where('j1.value','<=',$filters['experience_max']);
            }
            else if(array_key_exists('compensation_max',$filters) && array_key_exists('compensation_min',$filters))
            {
                $model = \DB::table('job_filters as j1')->select('j1.job_id')->JOIN('job_filters as j2','j2.job_id','=', 'j1.job_id')
                    ->where('j2.key','compensation_min')->where('j2.value','>=',$filters['compensation_min'])
                    ->where('j1.key','compensation_max')->where('j1.value','<=',$filters['compensation_max']);
            }
            else if($filter == 'experience_max' || $filter == 'compensation_max')
            {
                $model = static::selectRaw('distinct ' . static::$relatedColumn)
                    ->where('key',$filter)->where('value','<=',$value);
            }
            else if($filter == 'experience_min' || $filter == 'compensation_min')
            {
                $model = static::selectRaw('distinct ' . static::$relatedColumn)
                    ->where('key',$filter)->where('value','>=',$value);
            }
            else
            {
                $model = static::selectRaw('distinct ' . static::$relatedColumn)
                    ->where('key',$filter)->whereIn('value',$value);
            }

            if((null !== $skip) || (null !== $take)){
                $model = $model->skip($skip)->take($take);
            }

            $model = $model->orderBy(static::$relatedColumn)
                ->get()
                ->pluck(static::$relatedColumn);
            if(is_null($models)){
                $models = $model;
                continue;
            }

            $models = $model->intersect($models);
        }
        return $models;
    }

}