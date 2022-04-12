<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Surveys extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';
    
    protected $dates = ['created_at'];
    
    protected $label = 'Surveys';

    protected $fillable = ['id', 'survey_id', 'title', 'state', 'profile_id', 'company_id', 'payload_id'];
    
    protected $append = ['id', 'survey_id', 'title', 'state', 'profile_id', 'company_id', 'payload_id'];

    public function survey_participated_by()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'SURVEY_PARTICIPATION');
    }
}
