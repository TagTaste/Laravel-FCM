<?php namespace App\Cached\Filter;


class Profile extends BaseFilter
{
    protected $attributes = ['location','keywords','language'];
    
    public function getValueOfLocation()
    {
        return $this->model->city;
    }
    
    public function getValueOfKeywords()
    {
        return explode(",",$this->model->keywords);
    }
    
    public function getValueOfLanguage()
    {
        return explode(",",$this->model->expertise);
    }
    
    
}