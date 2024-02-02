<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DonationOrganisation;

class DonationProfileMapping extends Model
{
    protected $table = "donation_profile_mapping";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $guarded = ["id"];
    protected $fillable = ["profile_id","donation_organisation_id","created_at","updated_at"];


    public function getOrganisation(){
        return DonationOrganisation::where('id', $this->donation_organisation_id)->first();
    }
}