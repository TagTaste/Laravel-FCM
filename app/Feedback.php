<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;

    protected $fillable = ['ease_use','look_feel','feature','community','content','description','profile_id'];

    protected $visible = ['id','ease_use','look_feel','feature','community','content','description','profile_id'];

}
