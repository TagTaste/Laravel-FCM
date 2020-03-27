<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PollingOption extends Model
{
    use SoftDeletes;

    protected $table = 'poll_options';

    protected $fillable = ['text','poll_id','count','created_at','deleted_at','updated_at','image'];

    protected $visible = ['id','text','poll_id','count','created_at','deleted_at','updated_at','image'];
}
