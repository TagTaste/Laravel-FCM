<?php

namespace App;

use App\Scopes\Profile as ScopeProfile;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use ScopeProfile;

    protected $table = 'profile_books';
    protected $fillable = ['id','title','description','publisher','release_date','url','isbn'];

    protected $visible = ['id','title','description','publisher','release_date','url','isbn'];

}
