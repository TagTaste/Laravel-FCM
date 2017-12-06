<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invitation extends Model
{
    use SoftDeletes;

    //state mail-sent =1 , mail-opened =2 , registered = 3

    protected $table = 'invites';

    protected $fillable = ['invite_code', 'name', 'email', 'accepted','accepted_at','profile_id','source','state','mail_code','message'];

    protected $visible = ['invite_code', 'name', 'email', 'accepted','accepted_at','profile_id','source','state','mail_code','message'];

    static public $mailSent = 1;
    static public $registered = 3;

}
