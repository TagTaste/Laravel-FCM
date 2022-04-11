<?php

namespace App\Neo4j;

use Vinelab\NeoEloquent\Eloquent\SoftDeletes;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class PublicReviewProduct extends NeoEloquent
{
    use SoftDeletes;
    
    protected $connection = 'neo4j';
    
    protected $dates = ['created_at'];
    
    protected $label = 'Product';

    protected $fillable = ['id', 'product_id', 'name', 'is_active', 'is_newly_launched'];

    protected $append = ['id', 'product_id', 'name', 'is_active', 'is_newly_launched'];

    public function reviewed_by()
    {
        return $this->belongsToMany('\App\Neo4j\User', 'REVIEWED');
    }
}
