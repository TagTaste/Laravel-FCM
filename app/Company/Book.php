<?php

namespace App\Company;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;
use \App\Book as BaseBook;

class Book extends BaseBook
{
    protected $table = 'company_books';

    protected $fillable = ['id','title','description','publisher','release_date','url','isbn'];

    protected $visible = ['id','title','description','publisher','release_date','url','isbn'];

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
