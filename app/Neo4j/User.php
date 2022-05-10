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

    protected $fillable = ['id', 'profile_id', 'user_id', 'name', 'designation', 'handle', 'tagline', 'image_meta', 'verified', 'is_tasting_expert', 'isFollowing'];

    protected $append = ['id', 'profile_id', 'name', 'designation', 'handle', 'tagline', 'image_meta', 'verified', 'is_tasting_expert', 'isFollowing'];

    
    public function follows()
    {
        return $this->hasMany('\App\Neo4j\User', 'FOLLOWS');
    }

    public function followed_by()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'FOLLOWS');
    }

    public function follows_company()
    {
        return $this->hasMany('\App\Neo4j\Company', 'FOLLOWS_COMPANY');
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

    public function companies()
    {
        return $this->belongsToMany('\App\Neo4j\Company', 'FOLLOWS');
    }

    public function reviewed()
    {
        return $this->hasMany('\App\Neo4j\PublicReviewProduct', 'REVIEWED');
    }

    public function participated()
    {
        return $this->hasMany('\App\Neo4j\Polling', 'POLL_PARTICIPATION');
    }

    public function survey_participated()
    {
        return $this->hasMany('\App\Neo4j\Surveys', 'SURVEY_PARTICIPATION');
    }

    public function shown_interest()
    {
        return $this->hasMany('\App\Neo4j\Collaborate', 'SHOWN_INTEREST');
    }
}
