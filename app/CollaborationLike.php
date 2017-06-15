<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborationLike extends Model
{
    protected $table='collaboration_likes';

    protected $visible=['profile_id','collaboration_id'];
}
