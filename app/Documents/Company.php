<?php namespace App\Documents;

class Company extends Document
{
    public $type = 'company';
    
    public $bodyProperties = ['name','cuisines','profileId','productCatalogue','speciality','about','city','registered_address'];
}