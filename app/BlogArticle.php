<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogArticle extends Model
{
    protected $fillable = ['content','image','article_id'];

    public static $expectsFiles = true;

    public static $fileInputs = ['image' => 'blogs/images'];

    public function article() {
        return $this->belongsTo('\App\Article','article_id');
    }
}
