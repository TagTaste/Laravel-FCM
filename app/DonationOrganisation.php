<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonationOrganisation extends Model
{
    protected $table = "donation_organisations";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];
    protected $fillable = ["title","description","image_url","slug","sort_order","is_active","created_at","updated_at","deleted_at"];
    
    
}