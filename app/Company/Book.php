<?php

namespace App\Company;

use App\Book as BaseBook;
use Illuminate\Database\Eloquent\Builder;

class Book extends BaseBook
{
    protected $table = 'company_books';

    protected $fillable = ['id','title','description','publisher','release_date','url','isbn','profile_id'];

    protected $visible = ['id','title','description','publisher','release_date','url','isbn'];

    protected static function boot()
    {
        parent::boot();
        // Order by name ASC
        static::addGlobalScope('company_books', function (Builder $builder) {
            $builder->orderBy('release_date', 'desc');
        });
    }

    public function setReleaseDateAttribute($value)
    {
        if(!empty($value)){
            $value = "01-".$value ;
            $this->attributes['release_date'] = date('Y-m-d',strtotime($value));
        }
    }

    public function getReleaseDateAttribute($value)
    {
        if(!empty($value)){
            return date("m-Y",strtotime($value));
        }
    }
}
