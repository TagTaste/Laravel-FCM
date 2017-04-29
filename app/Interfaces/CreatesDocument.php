<?php namespace App\Interfaces;


use Illuminate\Database\Eloquent\Model;

interface CreatesDocument
{
    public static function create(Model $model);
}