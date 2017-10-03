<?php namespace App\Cached\Filter;

use App\Company\Type;

class Company extends BaseFilter
{
    protected $attributes = ['location','keywords','speciality','type'];
    
    public function getValueOfLocation()
    {
        return $this->model->city;
    }
    
    public function getValueOfKeywords()
    {
        return explode(",",$this->model->keywords);
    }
    
    public function getValueOfSpeciality()
    {
        return explode(",",$this->model->speciality);
    }
    
    public function getValueOfType()
    {
        $types = \Cache::remember("company_types",10,function(){
            return Type::select("id","name")->get()->pluck("name",'id');
        });
        
        return !is_null($this->model->type) && isset($types[$this->model->type]) ? $types[$this->model->type] : false;
    }
}