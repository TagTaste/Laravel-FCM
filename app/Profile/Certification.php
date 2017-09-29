<?php

namespace App\Profile;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use Profile;

    protected $fillable = ['name','description','date','profile_id'];

    protected $visible = ['name','description','date'];
    
}
