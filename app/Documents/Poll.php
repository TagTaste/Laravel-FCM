<?php namespace App\Documents;

class Poll extends Document
{
    public $type = 'poll';
    
    public $bodyProperties = ['id', 'title',
        'image_meta', 'type',  'created_at', 'updated_at','is_expired','expired_at'];
}