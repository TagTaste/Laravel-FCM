<?php

namespace App\Recipe;

use App\CollaborateCategory;
use Storage;
use App\Collaborate as BaseCollaborate;

class Collaborate extends BaseCollaborate
{
    protected $fillable = [];

    protected $visible = ['id','title', 'i_am', 'looking_for',
        'expires_on','video','location','categories',
        'description','project_commences',
        'duration','financials','eligibility_criteria','occassion',
        'profile_id', 'company_id','template_fields','template_id','notify','privacy_id',
        'created_at','deleted_at','file1','deliverables','start_in','state','updated_at',
        'profile','company','images','is_taster_residence','addresses','categories','type','collaborate_type'];

    protected $with = ['profile','company','addresses','categories'];


    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function categories()
    {
        return $this->belongsTo(CollaborateCategory::class,'category_id');
    }

    public function addresses()
    {
        return $this->hasMany('App\Collaborate\Addresses');
    }


}
