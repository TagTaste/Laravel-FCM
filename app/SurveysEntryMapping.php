<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveysEntryMapping extends Model
{
    
    protected $table = "surveys_entry_mapping";
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $guarded = ["id"];
    protected $fillable = ["surveys_attempt_id","created_at","updated_at"];

}
