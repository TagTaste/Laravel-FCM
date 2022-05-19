<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Polling extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';
    
    protected $dates = ['created_at'];
    
    protected $label = 'Polling';

    protected $fillable = ['id', 'poll_id', 'title', 'is_expired', 'profile_id', 'company_id', 'payload_id'];

    protected $append = ['id', 'poll_id', 'title', 'is_expired', 'profile_id', 'company_id','payload_id'];

    public function participated_by()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'POLL_PARTICIPATION');
    }
}
