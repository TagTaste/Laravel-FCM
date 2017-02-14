<?php

namespace App\Profile;

use App\Scopes\Profile;
use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use StartEndDate, Profile;

    protected $table = 'profile_shows';
    protected $fillable = ['id','title','description','channel',
        'current','start_date','end_date','url','appeared_as'];
    protected $visible = ['id','title','description','channel',
        'current','start_date','end_date','url','appeared_as'];
}
