<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    protected $fillable = ['name','description', 'image'];

    protected $visible = ['id','name','description', 'image'];

    public function profile()
    {
        return $this->belongsToMany('App\Profile','profiles_allergens','allergens_id','profile_id');
    }
}
