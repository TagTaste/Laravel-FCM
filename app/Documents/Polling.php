<?php namespace App\Documents;

class Polling extends Document
{
    public $type = 'polling';
    
    public $bodyProperties = ['id', 'title',
        'image_meta', 'type',  'created_at', 'updated_at','is_expired','expired_at'];
}