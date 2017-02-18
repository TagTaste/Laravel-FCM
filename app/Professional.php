<?php

namespace App;

use App\Scopes\Profile;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use Profile;

    protected $fillable = ['ingredients','cuisine','favourite_moments','famous_recipes','profile_id'];

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }

    public function expertAtCuisine()
    {
        return $this->belongsToMany('App\Cuisine','cuisine_professionals','professional_id','cuisine_id');
    }

    public function expertAtEstablishmentTypes()
    {
        return $this->belongsToMany('App\EstablishmentTypes','establishment_type_professionals','professional_id','establishment_type_id');
    }
}
