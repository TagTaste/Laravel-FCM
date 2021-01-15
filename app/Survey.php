<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{


    protected $table = "survey";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $incrementing = false;


    protected $fillable = ["id","profile_id","company_id","title","description","image_meta","media_meta","form_json","profile_updated_by","invited_profile_ids","expiry_date","is_active","state","deleted_at","published_at"];
    


    protected $cast = [
        "form_json" => 'json'
    ];
}
