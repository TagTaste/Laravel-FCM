<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaborate extends Model
{
    protected $fillable = ['title', 'i_am', 'looking_for', 'purpose', 'deliverables', 'who_can_help', 'expires_on', 'profile_id', 'company_id'];
}
