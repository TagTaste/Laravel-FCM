<?php namespace App\Documents;

class Profile extends Document
{
    public $type = 'profiles';
    
    public $bodyProperties = ['name'];
}