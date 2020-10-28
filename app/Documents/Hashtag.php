<?php namespace App\Documents;

class Hashtag extends Document
{
    public $type = 'hashtag';
    
    public $bodyProperties = ['id','tag', 'public_use','total_use','created','updated'];
}