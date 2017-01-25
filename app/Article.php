<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'user_id', 'privacy_id', 'comments_enabled', 'status', 'template_id'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $with = ['dish'];

    protected $visible = ['title','comments_enabled','dish','created_at'];

    public static function boot()
    {
        parent::boot();

        self::deleting(function($article){
            if($article->dish){
                $article->dish->delete();
            }
            if($article->blog){
                $article->blog->delete();
            }
        });


    }

    public function getCreatedAtAttribute($value)
    {
        $date = new Carbon($value);
        return $date->diffForHumans(Carbon::now(),true);
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

    public function privacy()
    {
        return $this->belongsTo('\App\Privacy', 'privacy_id');
    }

    public function template()
    {
        return $this->belongsTo('\App\Template', 'template_id');
    }

    public function dish()
    {
        return $this->hasOne('\App\DishArticle', 'article_id');
    }

    public function blog()
    {
        return $this->hasOne('\App\BlogArticle', 'article_id');
    }

    public function getContent()
    {
        return $this->getArticle()->content;
    }

    public function hasRecipe()
    {
        if ($this->dish) {
            return ($this->dish->recipe);
        }
        return false;
    }

    public function getAuthor()
    {
        return $this->user->name;
    }

    public function getView()
    {
        return $this->template->view;
    }

    public function getArticle()
    {
        $type = strtolower($this->template->type->name);
        return $this->$type;
    }

    public function getImage()
    {
        return $this->getArticle()->image;
    }

    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_articles','article_id','ideabook_id');
    }

}
