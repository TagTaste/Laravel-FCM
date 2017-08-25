<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $table = 'invites';
    protected $fillable = ['invite_code', 'name', 'email', 'accepted','accepted_at'];

}
