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

    protected $fillable = ['id', 'profile_id', 'user_id', 'name', 'designation', 'handle', 'tagline', 'image_meta', 'isFollowing'];

    protected $append = ['id', 'profile_id', 'name', 'designation', 'handle', 'tagline', 'image_meta', 'isFollowing'];

    
    public function follows()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'FOLLOWS');
    }

    public function dateOfBirth()
    {
        return $this->belongsToMany('\App\Neo4j\DateOfBirth', 'HAVE');
    }

    public function cuisines()
    {
        return $this->belongsToMany('\App\Neo4j\Cuisines', 'HAVE');
    }

    public function foodieType()
    {
        return $this->belongsTo('\App\Neo4j\FoodieType', 'HAVE');
    }

    public function degree()
    {
        return $this->belongsToMany('\App\Neo4j\Degree', 'HAVE');
    }

    public function experiance()
    {
        return $this->belongsToMany('\App\Neo4j\Experiance', 'HAVE');
    }
}
