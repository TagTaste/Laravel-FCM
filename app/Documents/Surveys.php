<?php namespace App\Documents;

class Surveys extends Document
{
    public $type = 'surveys';
    
    public $bodyProperties = ['id', 'title', 'description',
        'state', 'created_at', 'updated_at', 'image_meta','video_meta'];
}