<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogArticle extends Model
{
    protected $fillable = ['content','image','article_id'];
}
