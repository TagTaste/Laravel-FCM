<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Relation extends NeoEloquent
{
    protected $label = 'Relation';
    protected $guarded = [];
    public $timestamps = false;
    protected $fillable = ['relation_name','relation_description'];
    public function subject()
    {
        return $this->morphMany('App\Neo4j\User','TO');
    }
}