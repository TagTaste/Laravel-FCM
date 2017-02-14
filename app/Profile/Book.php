<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use Profile;

    protected $table = 'profile_books';

    protected $fillable = ['id','title','description','publisher','release_date','url','isbn'];


    protected $visible = ['id','title','description','publisher','release_date','url','isbn'];

    public function setReleaseDateAttribute($value)
    {
        $this->attributes['release_date'] = date('Y-m-d',strtotime($value));
    }

    public function getReleaseDateAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }
}
