<?php namespace App\Documents;

class Surveys extends Document
{
    public $type = 'surveys';
    
    public $bodyProperties = ['title', 'description',
        'state', 'created_at', 'updated_at'];
}
