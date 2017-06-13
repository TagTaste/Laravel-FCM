<?php namespace App\Documents;

class Profile extends Document
{
    public $type = 'profile';
    
    public $bodyProperties = ['name','handle','ingredients'];
}