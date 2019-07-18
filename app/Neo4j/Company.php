<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Company extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';

    protected $dates = ['deleted_at'];

    protected $label = 'Company';

    protected $fillable = ['id', 'profileId', 'name', 'logo_meta', 'company_id'];

    protected $append = ['id', 'profileId', 'name', 'logo_meta', 'company_id'];

    public function followed_by()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'FOLLOWS_COMPANY');
    }

}
