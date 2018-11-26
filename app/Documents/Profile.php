<?php namespace App\Documents;

class Profile extends Document
{
    public $type = 'profile';
    
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
        return $this->model->profile_occupations()->select('name')->get()->pluck('name')->toArray();
    }

    public function getValueOfSpecialization()
    {
        return $this->model->profile_specializations()->select('name')->get()->pluck('name')->toArray();
    }
}