<?php namespace App\Documents;

class Job extends Document
{
    public $type = 'job';
    
    public $bodyProperties = ['title', 'description', 'location', 'functional_area', 'key_skills', 'expected_role',
        'experience_required'];
}