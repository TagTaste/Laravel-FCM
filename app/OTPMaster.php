<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OTPMaster extends Model
{

    protected $table = 'otp_master';
    protected $guarded = ['id'];
}
