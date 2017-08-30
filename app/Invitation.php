<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use SoftDeletes;

    protected $table = 'invites';
    protected $fillable = ['invite_code', 'name', 'email', 'accepted','accepted_at'];

}
