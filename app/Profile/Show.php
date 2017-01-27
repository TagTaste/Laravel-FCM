<?php

namespace App\Profile;

use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use StartEndDate;

    protected $table = 'profile_shows';

    protected $visible = ['title','description','channel',
        'current','start_date','end_date','url','appeared_as'];
}
