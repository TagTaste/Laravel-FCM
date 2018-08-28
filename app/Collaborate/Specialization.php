<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Specialization extends Model {

    protected $table = 'collaborate_profiles_specialization';

    protected $fillable = ['collaborate_id','specialization_id'];

    protected $visible = ['name','description'];

    protected $appends = ['name','description'];

    protected $spcialzation = null;

    public function getNameAttribute()
    {
        $this->spcialzation = \DB::table('profiles_specialization')->where('id',$this->specialization_id)->first();

        return isset($this->spcialzation->name) ? $this->spcialzation->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->spcialzation->description) ? $this->spcialzation->description : null;
    }

}
