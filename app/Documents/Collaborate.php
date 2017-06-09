<?php namespace App\Documents;

class Collaborate extends Document
{
    public $type = 'collaborate';
    
    public $bodyProperties = ['id','title', 'i_am', 'looking_for',
        'purpose', 'deliverables', 'who_can_help', 'keywords','location'];
}