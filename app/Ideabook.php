<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ideabook extends Model
{
    protected $fillable = ['name','description','privacy_id','user_id'];

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function privacy()
    {
        return $this->belongsTo('\App\Privacy');
    }

    public function articles()
    {
        return $this->belongsToMany('\App\Article','ideabook_articles','ideabook_id','article_id');
    }

}
