<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Allergens extends Model {

    protected $table = 'collaborate_allergens';

    protected $fillable = ['collaborate_id','allergens_id'];

    protected $visible = ['id','name','description'];

    protected $appends = ['id','name','description'];

    protected $allergens = null;

    public function getIdAttribute()
    {
        $this->allergens = \DB::table('allergens')->where('id',$this->allergens_id)->first();
        return isset($this->allergens->id) ? $this->allergens->id : null;
    }

    public function getNameAttribute()
    {
        return isset($this->allergens->name) ? $this->allergens->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->allergens->description) ? $this->allergens->description : null;
    }
}
