<?php namespace App\Documents;

class Profile extends Document
{
    public $type = 'people';
    
    public $bodyProperties = ['name','handle','about','occupation','specialization','city','company','college'];
    
    public function getValueOfCollege()
    {
        return $this->model->education()->select('college')->get()->pluck('college')->toArray();
    }

    public function getValueOfCompany()
    {
        return $this->model->experience()->select('company')->get()->pluck('company')->toArray();
    }

    public function getValueOfOccupation()
    {
        $occuptions = $this->model->profile_occupations;
        $occuptionName = [];
        foreach ($occuptions as $occuption)
        {
            $occuptionName[] = $occuption->name;
        }
        \Log::info("count of ".count($occuptionName));
        return $occuptionName;
    }

    public function getValueOfSpecialization()
    {
        $specializations = $this->model->profile_specializations;
        $specializationName = [];
        foreach ($specializations as $specialization)
        {
            $specializationName[] = $specialization->name;
        }
        \Log::info("count of ".count($specializationName));
        return $specializationName;
    }
}