<?php namespace App\Documents;

class Recipe extends Document
{
    public $type = 'recipe';
    
    public $bodyProperties = ['name','equipments','ingredients'];
}