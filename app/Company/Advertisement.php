<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['id','title','description','youtube_url'
    ,'video'];

    protected $visible = ['id','title','description','youtube_url'];
}
