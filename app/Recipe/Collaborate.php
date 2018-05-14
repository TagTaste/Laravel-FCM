<?php

namespace App\Recipe;

use Storage;
use App\Collaborate as BaseCollaborate;

class Collaborate extends BaseCollaborate
{
    protected $fillable = [];

    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences','images',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id',
        'profile','company','created_at','deleted_at',
        'applicationCount','file1','deliverables','start_in','state','updated_at'];


}
