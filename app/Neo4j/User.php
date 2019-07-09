<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class User extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';    

    protected $table = 'Users';

    protected $dates = ['deleted_at'];

    protected $label = 'User';

    protected $fillable = ['userId', 'profileId', 'handle', 'imageMeta', 'name', 'designation', 'imageUrl'];

    protected $append = ['userId', 'profileId', 'handle', 'imageMeta', 'name', 'designation', 'imageUrl'];

    
    public function follows()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'FOLLOWS');
    }
}
