<?php namespace App\Documents;

use Illuminate\Database\Eloquent\Model;

class Profile extends Document
{
    public $type = 'profiles';
    
    public $bodyProperties = ['name'];
}