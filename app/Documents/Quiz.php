<?php namespace App\Documents;

class Quiz extends Document
{
    public $type = 'quiz';
    
    public $bodyProperties = ['id', 'title', 'description',
        'state', 'created_at', 'updated_at', 'image_meta'];
}