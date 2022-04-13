<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Collaborate extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';
    
    protected $dates = ['created_at'];
    
    protected $label = 'Collaborate';

    protected $fillable = ['id', 'collaborate_id', 'title', 'state', 'collaborate_type','profile_id', 'company_id', 'payload_id'];
    
    protected $append = ['id', 'collaborate_id', 'title', 'state', 'collaborate_type','profile_id', 'company_id', 'payload_id'];

    public function shown_interest_by()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'SHOWN_INTEREST');
    }
}
