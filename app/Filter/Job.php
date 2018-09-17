<?php

namespace App\Filter;

use App\Filter;

class Job extends Filter {
    private static $maxFilters = 6;

    protected $table = "job_filters";

    protected $strings = ['location','expected_role','Joining'=>'joining','experience_min','experience_max','compensation_min'=>'salary_min'
        ,'compensation_max'=>'salary_max'];
    
    protected $models = ['By Company'=>'company.name','Occupation Type' => 'jobType.name',
        'By Person'=> 'profile.name'];
    
    public static $cacheKey = "job:";
    
    public static $relatedColumn = 'job_id';

    public static $filterOrder = ['Occupation Type','location','Experience','Compensation'
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
                    $filters['Experience'][0]['value'] = '0 - 2 years';
                    $filters['Experience'][1]['value'] = '2 - 5 years';
                    $filters['Experience'][2]['value'] = '5 - 8 years';
                    $filters['Experience'][3]['value'] = '8 - 10 years';
                    $filters['Experience'][4]['value'] = '> 10 years';
                    $filters['Compensation'][0]['value'] = '0 - 3.5 LPA';
                    $filters['Compensation'][1]['value'] = '3.5 - 7 LPA';
                    $filters['Compensation'][2]['value'] = '7 - 15 LPA';
                    $filters['Compensation'][3]['value'] = '> 15 LPA';
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
        return $filters;
    }


    public static function getModelIds(&$filters,$skip = null,$take = null)
    {
        $models = null;
        if(array_key_exists('Experience',$filters))
        {
            $experience = explode('years', $filters['Experience'][0]);
            $experience = htmlspecialchars_decode($experience[0],ENT_QUOTES);
            if($experience == '> 10 ')
            {
//                $experience = explode(' ', $experience);
//                $minExperience = null;
                $maxExperience = 10;
                $model = \DB::table('job_filters as j1')->select('j1.job_id')->where(function($query) use ($maxExperience){
                        $query->where('j1.key','experience_min')->whereRaw('CAST(j1.value as DECIMAL(9,2)) >= '.(double)$maxExperience);
                    });
            }
            else
            {
                $experience = explode(' - ', $experience);
                $minExperience = $experience[0];
                $maxExperience = $experience[1];
                $model = \DB::table('job_filters as j1')->select('j1.job_id')->JOIN('job_filters as j2','j2.job_id','=', 'j1.job_id')
                    ->where(function($query) use ($minExperience){
                        $query->where('j2.key','experience_min')->whereRaw('CAST(j2.value as DECIMAL(9,2)) >= '.(double)$minExperience);
                    })->where(function($query) use ($maxExperience){
                        $query->where('j1.key','experience_min')->whereRaw('CAST(j1.value as DECIMAL(9,2)) < '.(double)$maxExperience);
                    });
            }
            if((null !== $skip) || (null !== $take)){
                $model = $model->skip($skip)->take($take);
            }

            $model = $model->orderBy(static::$relatedColumn)
                ->get()
                ->pluck(static::$relatedColumn);
            if(is_null($models)){
                $models = $model;
            }
            else{
                $models = $model->intersect($models);

            }
        }
        else if(array_key_exists('Compensation',$filters))
        {
            $compensation = explode('LPA', $filters['Compensation'][0]);
            $compensation = htmlspecialchars_decode($compensation[0],ENT_QUOTES);

            if($compensation == '> 15 ')
            {
//                $compensation = explode(' ', $compensation);
//                $minCompensation = null;
                $maxCompensation = 15;
                $model = \DB::table('job_filters as j1')->select('j1.job_id')->where(function($query) use ($maxCompensation){
                        $query->where('j1.key','compensation_min')->whereRaw('CAST(j1.value as UNSIGNED) >= '.(int)$maxCompensation);
                    });
            }
            else
            {
                $compensation = explode(' - ', $compensation);
                $minCompensation = $compensation[0];
                $maxCompensation = $compensation[1];
                $model = \DB::table('job_filters as j1')->select('j1.job_id')->JOIN('job_filters as j2','j2.job_id','=', 'j1.job_id')
                    ->where(function($query) use ($minCompensation){
                        $query->where('j2.key','compensation_min')->whereRaw('CAST(j2.value as UNSIGNED) >= '.(int)$minCompensation);
                    })->where(function($query) use ($maxCompensation){
                        $query->where('j1.key','compensation_min')->whereRaw('CAST(j1.value as UNSIGNED) < '.(int)$maxCompensation);
                    });
            }

            if((null !== $skip) || (null !== $take)){
                $model = $model->skip($skip)->take($take);
            }

            $model = $model->orderBy(static::$relatedColumn)
                ->get()
                ->pluck(static::$relatedColumn);
            if(is_null($models)){
                $models = $model;
            }
            else{
                $models = $model->intersect($models);

            }
        }
        foreach($filters as $filter => $value){
            if(array_key_exists('Experience',$filters)||array_key_exists('Compensation',$filters))
            {
                continue;
            }
            $model = static::selectRaw('distinct ' . static::$relatedColumn)
                ->where('key',$filter)->whereIn('value',$value);

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