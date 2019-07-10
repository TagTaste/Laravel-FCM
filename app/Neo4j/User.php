<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class User extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';    

    protected $dates = ['deleted_at'];

    protected $label = 'User';

    protected $fillable = ['id', 'user_id', 'name', 'designation', 'handle', 'tagline', 'image_meta', 'isFollowing'];

    protected $append = ['id', 'user_id', 'name', 'designation', 'handle', 'tagline', 'image_meta', 'isFollowing'];

    
    public function follows()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'FOLLOWS');
    }

    // public function dateOfBirth()
    // {
    //     return $this->belongsTo('\App\Neo4j\DateOfBirth', 'HAVE');
    // }
}
