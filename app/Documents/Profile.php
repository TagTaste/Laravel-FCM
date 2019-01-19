<?php namespace App\Documents;

class Profile extends Document
{
    public $type = 'profile';
    
    public $bodyProperties = ['name','handle','ingredients','about','address','interests','expertise','keywords','city',
        'college','company','occupation','specialization'];
    
    public function getValueOfcollege()
    {
        return $this->model->education()->select('college')->get()->pluck('college')->toArray();
    }

    public function getValueOfcompany()
    {
        return $this->model->experience()->select('company')->get()->pluck('company')->toArray();
    }

    public function getValueOfoccupation()
    {
        $occuptions = $this->model->profile_occupations;
        $occuptionName = [];
        foreach ($occuptions as $occuption)
        {
            $occuptionName[] = $occuption->name;
        }
        return $occuptionName;
    }

    public function getValueOfspecialization()
    {
        $specializations = $this->model->profile_specializations;
        $specializationName = [];
        foreach ($specializations as $specialization)
        {
            $specializationName[] = $specialization->name;
        }
        return $specializationName;
    }
}