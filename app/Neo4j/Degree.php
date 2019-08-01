<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Degree extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';

    protected $dates = ['deleted_at'];

    protected $label = 'Degree';

    protected $fillable = ['name'];

    public function have()
    {
        return $this->hasMany('App\Neo4j\User', 'HAVE');
    }

}
