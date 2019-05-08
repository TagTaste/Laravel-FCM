<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PollingVote extends Model
{
    use SoftDeletes;
    protected $table = 'poll_votes';

    protected $fillable = ['profile_id','poll_id','poll_option_id','ip_address'];

    protected $visible = ['id','text','poll_id','count','created_at','deleted_at','updated_at'];
}
