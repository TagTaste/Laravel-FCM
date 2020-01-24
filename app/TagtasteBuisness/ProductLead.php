<?php

namespace App\TagtasteBuisness;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class ProductLead extends Model
{
    use SoftDeletes;
    
    protected $connection = 'mysql_ttfb';

    protected $dates = ['deleted_at'];

    protected $table = 'ttfb_product_leads';

    protected $fillable = ['id', 'product_id', 'name', 'phone', 'email', 'designation', 'address', 'profile_id', 'additional_info', 'distributor_id', 'lead_source', 'current_status', 'creator_id', 'creator_grp_id', 'created_at', 'updated_at', 'deleted_at'];
}