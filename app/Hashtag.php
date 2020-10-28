<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    Protected $fillable = ['id','tag', 'public_use','total_use','created','updated'];

    Protected $visible = ['id','tag', 'public_use','total_use','created','updated'];
}
