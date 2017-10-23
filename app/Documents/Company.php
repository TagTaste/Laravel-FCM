<?php namespace App\Documents;

class Company extends Document
{
    public $type = 'company';
    
    public $bodyProperties = ['name','cuisines','profileId','productCatalogue','speciality','about','city','registered_address'];
    
    public function getValueOfproductCatalogue()
    {
        return $this->model->productCatalogue()->select('product','category')->get()->pluck('product','category')->toArray();
    }
    
    public function getValueOfspeciality()
    {
        return explode(",",$this->model->speciality);
    }
}