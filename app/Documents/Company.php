<?php namespace App\Documents;

class Company extends Document
{
    public $type = 'company';
    
    public $bodyProperties = ['name','cuisines','profile_id'];
}