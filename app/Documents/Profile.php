<?php namespace App\Documents;

class Profile extends Document
{
    public $type = 'profile';
    
    public $bodyProperties = ['name','handle','ingredients','about','address','interests','expertise','keywords','city','college'];
    
    public function getValueOfCollege()
    {
        return $this->model->education()->select('college')->get()->pluck('college')->toArray();
    }
}