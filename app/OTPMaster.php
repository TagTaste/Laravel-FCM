<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OTPMaster extends Model
{
 use SoftDeletes;
    protected $table = 'otp_master';
    protected $guarded = ['id'];

    const UPDATED_AT = null;
    
}
