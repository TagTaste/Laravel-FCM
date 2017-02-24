<?php

namespace App\Company;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;
use \App\Book as BaseBook;

class Book extends BaseBook
{
    protected $table = 'company_books';
}
