<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;


class Specialization extends Model {

    protected $table = 'profile_specializations';

    protected $fillable = ['profile_id','specialization_id'];

    protected $visible = ['id','name','description', 'image'];

    protected $appends = ['id','name','description', 'image'];

    protected $spcialzation = null;

    public function getIdAttribute()
    {
        $this->spcialzation = \DB::table('specializations')->where('id',$this->specialization_id)->first();
        return isset($this->spcialzation->id) ? $this->spcialzation->id : null;
    }

    public function getNameAttribute()
    {
        return isset($this->spcialzation->name) ? $this->spcialzation->name : null;
    }

    public function getDescriptionAttribute()
    {
        return isset($this->spcialzation->description) ? $this->spcialzation->description : null;
    }

    public function getImageAttribute()
    {
        return isset($this->spcialzation->image) ? $this->spcialzation->image : null;
    }
}
